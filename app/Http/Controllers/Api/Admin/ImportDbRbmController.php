<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MstRbm; // Pastikan model MstRbm sudah dibuat

class ImportDbRbmController extends Controller
{
    /**
     * Handle file upload and import Excel data to mst_rbm table.
     */
    public function importExcel(Request $request)
    {
        // Validasi file dan tahun
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls',
            'year' => 'required|numeric|in:2023,2024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $year = $request->input('year'); // Ambil tahun yang dipilih

        try {
            // Hapus semua data berdasarkan tahun yang dipilih
            MstRbm::where('year', $year)->delete();

            // Baca data Excel
            $data = Excel::toArray([], $file)[0];
            $data = array_slice($data, 1); // Lewati header

            // Masukkan data baru ke database
            foreach ($data as $row) {
                MstRbm::create([
                    'patrol_id' => $row[0] ?? null,
                    'type' => $row[1] ?? null,
                    'patrol_start_date' => $row[2] ?? null,
                    'patrol_end_date' => $row[3] ?? null,
                    'station' => $row[4] ?? null,
                    'team' => $row[5] ?? null,
                    'objective' => $row[6] ?? null,
                    'mandate' => $row[7] ?? null,
                    'patrol_leg_id' => $row[8] ?? null,
                    'leader' => $row[9] ?? null,
                    'patrol_transport_type' => $row[10] ?? null,
                    'waypoint_id' => $row[11] ?? null,
                    'waypoint_date' => $row[12] ?? null,
                    'waypoint_time' => $row[13] ?? null,
                    'last_modified' => $row[14] ?? null,
                    'last_modified_by' => $row[15] ?? null,
                    'observation_category_0' => $row[16] ?? null,
                    'observation_category_1' => $row[17] ?? null,
                    'jenis_tumbuhan' => $row[18] ?? null,
                    'kesesuaian_regulasi' => $row[19] ?? null,
                    'keterangan' => $row[20] ?? null,
                    'kondisi_tumbuhan' => $row[21] ?? null,
                    'perlu_tindak_lanjut' => $row[22] ?? null,
                    'status_tindak_lanjut' => $row[23] ?? null,
                    'tanggal_tindak_lanjut' => $row[24] ?? null,
                    'tindakan' => $row[25] ?? null,
                    'tipe_temuan' => $row[26] ?? null,
                    'umur_satwa' => $row[27] ?? null,
                    'usia_temuan' => $row[28] ?? null,
                    'geometry' => $row[29] ?? null,
                    'date' => $row[30] ?? null,
                    'patrol_start_date_2' => $row[31] ?? null,
                    'patrol_end_date_2' => $row[32] ?? null,
                    'patrol_duration' => $row[33] ?? null,
                    'jenis_satwa' => $row[34] ?? null,
                    'year' => $year, // Tambahkan tahun ke kolom year
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
}
