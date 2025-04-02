<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RbmResource;
use Illuminate\Support\Facades\Validator;
use App\Models\MstWru; // Pastikan model MstWru sudah dibuat
use Illuminate\Support\Facades\DB;

class WruController extends Controller
{
    public function summaryAll(Request $request)
    {
        // Ambil parameter dari request
        $year = $request->input('year');
        $tgl = $request->input('tgl');

        // 1. Hitung jumlah berdasarkan kejadian
        $totalKejadian = MstWru::selectRaw('COUNT(DISTINCT kejadian) as total_kejadian')
            ->whereNotNull('kejadian') // Abaikan null
            ->where('kejadian', '!=', '') // Abaikan string kosong
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->get()
            ->first()
            ->total_kejadian;

        // 2. Hitung total Jenis TSL
        $totalJenisTsl = MstWru::selectRaw('COUNT(DISTINCT jenis_tsl) as total_tsl')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->get()
            ->first()
            ->total_tsl;

        // 3. Hitung total TSL
        $totalTsl = MstWru::selectRaw('COUNT(DISTINCT kode_tsl) as total_tsl')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->first()
            ->total_tsl;

        $listKejadian = MstWru::selectRaw('jenis_kejadian, COUNT(DISTINCT kejadian) as total')
            ->whereNotNull('jenis_kejadian') // Abaikan null
            ->where('jenis_kejadian', '!=', '') // Abaikan string kosong
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_kejadian')
            ->orderBy('total', 'desc')
            ->get();

        $listTsl = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslSerahan = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'SERAHAN', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKandang = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KANDANG TRANSIT', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKematian = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KEMATIAN TSL', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKonflik = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KONFLIK TSL', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslPelepasliaran = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'PELEPASLIARAN', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslTranslokasi = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'TRANSLOKASI', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslTitipan = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'TITIPAN', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslPatroli = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'PATROLI', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslLainnya = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'LAINNYA', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listPihakMenyerahkan = MstWru::selectRaw('serahan, COUNT(DISTINCT kejadian) as total')
            ->whereNotNull('serahan') // Abaikan null
            ->where('serahan', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'SERAHAN', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('serahan')
            ->orderBy('total', 'desc')
            ->get();

        $listPenyebabKematian = MstWru::selectRaw('penyebab_kematian, COUNT(kejadian) as total')
            ->whereNotNull('penyebab_kematian') // Abaikan null
            ->where('penyebab_kematian', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KEMATIAN TSL', '')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->groupBy('penyebab_kematian')
            ->orderBy('total', 'desc')
            ->get();

        $listKejadianKonflik = MstWru::select(
            'kejadian',
            'jenis_kejadian',
            'tgl',
            'lokasi',
            'koordinat',
            'kode_tsl',
            'jenis_tsl',
            'jml_tsl',
            'berita_acara',
            'penyebab_kematian',
            'serahan',
            'deskripsi_konflik',
            'penanganan_konflik',
            'keterangan',
            'foto',
        )
            ->where('jenis_kejadian', 'KONFLIK TSL')
            ->when($tgl, function ($query, $tgl) {
                return $query->whereRaw('YEAR(tgl) = ?', [$tgl]);
            })
            ->limit('2000')
            ->orderBy('tgl', 'desc')
            ->get();


        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'totalKejadian' => $totalKejadian,
                'totalTsl' => $totalTsl,
                'totalJenisTsl' => $totalJenisTsl,
                'listKejadian' => $listKejadian,
                'listTsl' => $listTsl,
                'listTslSerahan' => $listTslSerahan,
                'listTslKandang' => $listTslKandang,
                'listTslKematian' => $listTslKematian,
                'listTslKonflik' => $listTslKonflik,
                'listTslPelepasliaran' => $listTslPelepasliaran,
                'listTslTranslokasi' => $listTslTranslokasi,
                'listTslTitipan' => $listTslTitipan,
                'listTslPatroli' => $listTslPatroli,
                'listTslLainnya' => $listTslLainnya,
                'listPihakMenyerahkan' => $listPihakMenyerahkan,
                'listPenyebabKematian' => $listPenyebabKematian,
                'listKejadianKonflik' => $listKejadianKonflik

            ]
        ]);
    }

    public function maps(Request $request)
    {
        // Ambil parameter dari request
        $tahun = $request->input('tahun');
        $kejadian = $request->input('kejadian');
        $jenis_kejadian = $request->input('jenis_kejadian');
        $tgl = $request->input('tgl');
        $lokasi = $request->input('lokasi');
        $koordinat = $request->input('koordinat');
        $kode_tsl = $request->input('kode_tsl');
        $jenis_tsl = $request->input('jenis_tsl');
        $jml_tsl = $request->input('jml_tsl');
        $berita_acara = $request->input('berita_acara');
        $penyebab_kematian = $request->input('penyebab_kematian');
        $serahan = $request->input('serahan');
        $deskripsi_konflik = $request->input('deskripsi_konflik');
        $penanganan_konflik = $request->input('penanganan_konflik');

        // Cek apakah ada parameter request
        if ($request->hasAny([
            'tahun',
            'kejadian',
            'jenis_kejadian',
            'tgl',
            'lokasi',
            'koordinat',
            'kode_tsl',
            'jenis_tsl',
            'jml_tsl',
            'berita_acara',
            'penyebab_kematian',
            'serahan',
            'deskripsi_konflik',
            'penanganan_konflik',
        ])) {
            // Query data patroli dengan filter dinamis
            $patroliData = MstWru::select(
                'tahun',
                'kejadian',
                'jenis_kejadian',
                'tgl',
                'lokasi',
                'koordinat',
                'kode_tsl',
                'jenis_tsl',
                'jml_tsl',
                'berita_acara',
                'penyebab_kematian',
                'serahan',
                'deskripsi_konflik',
                'penanganan_konflik',
                'keterangan',
                'foto',
            )
                ->when($tahun, fn($query) => $query->where('tahun', 'like', "%$tahun%"))
                ->when($kejadian, fn($query) => $query->where('kejadian', 'like', "%$kejadian%"))
                ->when($jenis_kejadian, fn($query) => $query->where('jenis_kejadian', 'like', "%$jenis_kejadian%"))
                ->when($tgl, fn($query) => $query->whereDate('tgl', $tgl)) // Gunakan whereDate untuk tanggal
                ->when($lokasi, fn($query) => $query->where('lokasi', 'like', "%$lokasi%"))
                ->when($koordinat, fn($query) => $query->where('koordinat', 'like', "%$koordinat%"))
                ->when($kode_tsl, fn($query) => $query->where('kode_tsl', 'like', "%$kode_tsl%"))
                ->when($jenis_tsl, fn($query) => $query->where('jenis_tsl', 'like', "%$jenis_tsl%"))
                ->when($jml_tsl, fn($query) => $query->where('jml_tsl', $jml_tsl)) // Asumsi ini angka, tanpa LIKE
                ->when($berita_acara, fn($query) => $query->where('berita_acara', 'like', "%$berita_acara%"))
                ->when($penyebab_kematian, fn($query) => $query->where('penyebab_kematian', 'like', "%$penyebab_kematian%"))
                ->when($serahan, fn($query) => $query->where('serahan', 'like', "%$serahan%"))
                ->when($deskripsi_konflik, fn($query) => $query->where('deskripsi_konflik', 'like', "%$deskripsi_konflik%"))
                ->when($penanganan_konflik, fn($query) => $query->where('penanganan_konflik', 'like', "%$penanganan_konflik%"))
                ->limit('2000')
                ->get();
        } else {
            $patroliData = MstWru::select(
                'tahun',
                'kejadian',
                'jenis_kejadian',
                'tgl',
                'lokasi',
                'koordinat',
                'kode_tsl',
                'jenis_tsl',
                'jml_tsl',
                'berita_acara',
                'penyebab_kematian',
                'serahan',
                'deskripsi_konflik',
                'penanganan_konflik',
                'keterangan',
                'foto',
            )
                ->whereNotNull('kejadian') // Abaikan null
                // ->where('jenis_kejadian', '!=', 'SERAHAN') // Abaikan string kosong
                ->where('jenis_kejadian', '!=', 'KANDANG TRANSIT') // Abaikan string kosong
                // ->where('jenis_tsl', '!=', 'Koral (Tubipora musica)')
                // ->where('jenis_tsl', '!=', 'Teripang Susu Putih (Holothuria fuscogilva)')
                // ->where('jenis_tsl', '!=', 'Teripang Susu Koro (Holothuria nobilis)')
                // ->groupBy('kode_tsl')
                ->limit('2000')
                ->get();
        }

        // Cek apakah data kosong
        if ($patroliData->isEmpty()) {
            return new RbmResource(false, 'Data Sebaran TSL tidak ditemukan', []);
        }

        // Return data dalam format RbmResource
        return new RbmResource(true, 'Data Sebaran TSL ditemukan', $patroliData);
    }

    public function paramFilter()
    {
        $year = MstWru::selectRaw('tahun, COUNT(*) as total')
            ->whereNotNull('tahun') // Abaikan null
            ->where('tahun', '!=', '') // Abaikan string kosong
            ->groupBy('tahun')
            ->orderBy('total', 'desc')

            ->get();
        $listKejadian = MstWru::selectRaw('jenis_kejadian, COUNT(DISTINCT kejadian) as total')
            ->whereNotNull('jenis_kejadian') // Abaikan null
            ->where('jenis_kejadian', '!=', '') // Abaikan string kosong

            ->groupBy('jenis_kejadian')
            ->orderBy('total', 'desc')
            ->get();

        $listKodeTsl = MstWru::selectRaw('kode_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('kode_tsl') // Abaikan null
            ->where('kode_tsl', '!=', '') // Abaikan string kosong

            ->groupBy('kode_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTsl = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslSerahan = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'SERAHAN', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKandang = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KANDANG TRANSIT', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKematian = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KEMATIAN TSL', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslKonflik = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KONFLIK TSL', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslPelepasliaran = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'PELEPASLIARAN', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslTranslokasi = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'TRANSLOKASI', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslTitipan = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'TITIPAN', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslPatroli = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'PATROLI', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listTslLainnya = MstWru::selectRaw('jenis_tsl, COUNT(DISTINCT kode_tsl) as total')
            ->whereNotNull('jenis_tsl') // Abaikan null
            ->where('jenis_tsl', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'LAINNYA', '')

            ->groupBy('jenis_tsl')
            ->orderBy('total', 'desc')
            ->get();

        $listPihakMenyerahkan = MstWru::selectRaw('serahan, COUNT(DISTINCT kejadian) as total')
            ->whereNotNull('serahan') // Abaikan null
            ->where('serahan', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'SERAHAN', '')

            ->groupBy('serahan')
            ->orderBy('total', 'desc')
            ->get();

        $listPenyebabKematian = MstWru::selectRaw('penyebab_kematian, COUNT(kejadian) as total')
            ->whereNotNull('penyebab_kematian') // Abaikan null
            ->where('penyebab_kematian', '!=', '') // Abaikan string kosong
            ->where('jenis_kejadian', 'KEMATIAN TSL', '')

            ->groupBy('penyebab_kematian')
            ->orderBy('total', 'desc')
            ->get();

        $listKejadianKonflik = MstWru::select(
            'kejadian',
            'jenis_kejadian',
            'tgl',
            'lokasi',
            'koordinat',
            'kode_tsl',
            'jenis_tsl',
            'jml_tsl',
            'berita_acara',
            'penyebab_kematian',
            'serahan',
            'deskripsi_konflik',
            'penanganan_konflik',
            'keterangan',
            'foto',
        )
            ->where('jenis_kejadian', 'KONFLIK TSL')
            ->limit('2000')
            ->orderBy('tgl', 'desc')
            ->get();


        // Format data hasil
        $result = [
            'year' => $year,
            'listKejadian' => $listKejadian,
            'listKodeTsl' => $listKodeTsl,
            'listTsl' => $listTsl,
            'listTslSerahan' => $listTslSerahan,
            'listTslKandang' => $listTslKandang,
            'listTslKematian' => $listTslKematian,
            'listTslKonflik' => $listTslKonflik,
            'listTslPelepasliaran' => $listTslPelepasliaran,
            'listTslTranslokasi' => $listTslTranslokasi,
            'listTslTitipan' => $listTslTitipan,
            'listTslPatroli' => $listTslPatroli,
            'listTslLainnya' => $listTslLainnya,
            'listPihakMenyerahkan' => $listPihakMenyerahkan,
            'listPenyebabKematian' => $listPenyebabKematian,
            'listKejadianKonflik' => $listKejadianKonflik
        ];

        // Return hasil menggunakan RbmResource
        return new RbmResource(true, 'Data filter ditemukan', $result);
    }
}
