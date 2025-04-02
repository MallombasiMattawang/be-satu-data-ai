<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MstWru;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ImportDbWruController extends Controller
{
    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls',
            'year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $year = $request->input('year');

        try {
            // Hapus semua data berdasarkan tahun yang dipilih
            // MstWru::where('tahun', $year)->delete();
            MstWru::truncate();

            // Baca data Excel
            $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX)[0];
            $data = array_slice($data, 1); // Lewati header

            foreach ($data as $row) {
                MstWru::create([
                    'kejadian' => $this->getCellValue($row[0] ?? null),
                    'jenis_kejadian' => $this->getCellValue($row[1] ?? null),
                    'tgl' => isset($row[2]) ? $this->convertExcelDate($row[2]) : null,
                    'lokasi' => $this->getCellValue($row[3] ?? null),
                    'koordinat' => $this->getCellValue($row[4] ?? null),
                    'kode_tsl' => $this->getCellValue($row[5] ?? null),
                    'jenis_tsl' => $this->getCellValue($row[6] ?? null),
                    'jml_tsl' => $this->getCellValue($row[7] ?? null),
                    'berita_acara' => $this->getCellValue($row[8] ?? null),
                    'penyebab_kematian' => $this->getCellValue($row[9] ?? null),
                    'serahan' => $this->getCellValue($row[10] ?? null),
                    'deskripsi_konflik' => $this->getCellValue($row[11] ?? null),
                    'penanganan_konflik' => $this->getCellValue($row[12] ?? null),
                    'keterangan' => $this->getCellValue($row[13] ?? null),
                    'foto' => $this->getCellValue($row[14] ?? null),
                    'tahun' => $year,
                ]);
            }

            return response()->json([
                'message' => 'Data imported successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to import data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil hanya nilai sel, bukan formula
     */
    private function getCellValue($cell)
    {
        if ($cell instanceof Cell) {
            try {
                // Jika cell mengandung formula, ambil hasil kalkulasinya
                if ($cell->isFormula()) {
                    return $cell->getCalculatedValue();
                }
                return $cell->getValue();
            } catch (\Exception $e) {
                return null; // Jika formula error, set null agar tidak crash
            }
        }
        return $cell;
    }

    /**
     * Konversi tanggal Excel ke format Y-m-d
     */
    private function convertExcelDate($excelDate)
    {
        try {
            // Jika format angka (serial number Excel), ubah ke tanggal
            if (is_numeric($excelDate)) {
                return Carbon::instance(Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
            }

            // Jika format teks (misal: "11/4/2023"), parse ke format yang benar
            return Carbon::parse($excelDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Jika parsing gagal, set null agar tidak error
        }
    }
}