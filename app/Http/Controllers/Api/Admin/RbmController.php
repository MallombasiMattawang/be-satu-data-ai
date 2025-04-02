<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RbmResource;
use Illuminate\Support\Facades\Validator;
use App\Models\MstRbm; // Pastikan model MstRbm sudah dibuat
use Illuminate\Support\Facades\DB;

class RbmController extends Controller
{
    public function summaryAll(Request $request)
    {
        // Ambil parameter dari request
        $year = $request->input('year');

        // 1. Hitung jumlah berdasarkan PATROLI_ID
        $totalPatroli = MstRbm::selectRaw('COUNT(DISTINCT patrol_id, year) as total_patroli')
            ->whereNotNull('patrol_id') // Abaikan null
            ->where('patrol_id', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_patroli;

        $totalTitik = MstRbm::whereNotNull('patrol_id')
            ->where('patrol_id', '!=', '')
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->count();

        // 2. Hitung jumlah berdasarkan STATION
        $totalStation = MstRbm::selectRaw('COUNT(DISTINCT station) as total_station')
            ->whereNotNull('station') // Abaikan null
            ->where('station', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_station;

        $listStation = MstRbm::selectRaw('station, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('station') // Abaikan null
            ->where('station', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('station')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Hitung jumlah berdasarkan Type
        $totalType = MstRbm::selectRaw('COUNT(DISTINCT type) as total_type')
            ->whereNotNull('type') // Abaikan null
            ->where('type', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_type;

        $listType = MstRbm::selectRaw('type, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('type') // Abaikan null
            ->where('type', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get();

        // 4. Hitung jumlah berdasarkan Team
        $totalTeam = MstRbm::selectRaw('COUNT(DISTINCT team) as total_team')
            ->whereNotNull('team') // Abaikan null
            ->where('team', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_team;

        $listTeam = MstRbm::selectRaw('team, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('team') // Abaikan null
            ->where('team', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('team')
            ->orderBy('total', 'desc')
            ->get();

        // 5. Hitung jumlah berdasarkan Mandate
        $totalMandate = MstRbm::selectRaw('COUNT(DISTINCT mandate) as total_mandate')
            ->whereNotNull('mandate') // Abaikan null
            ->where('mandate', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_mandate;

        $listMandate = MstRbm::selectRaw('mandate, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('mandate') // Abaikan null
            ->where('mandate', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            // ->when($resort, function ($query, $resort) {
            //     return $query->where('id', $resort);
            // })


            ->groupBy('mandate')
            ->orderBy('total', 'desc')
            ->get();

        // 6. Hitung jumlah berdasarkan Leader
        $totalLeader = MstRbm::selectRaw('COUNT(DISTINCT leader) as total_leader')
            ->whereNotNull('leader') // Abaikan null
            ->where('leader', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_leader;

        $listLeader = MstRbm::selectRaw('leader, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('leader') // Abaikan null
            ->where('leader', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('leader') // Group berdasarkan leader
            ->orderBy('total', 'desc') // Urutkan berdasarkan total secara descending
            ->get();

        // 7. Hitung jumlah berdasarkan PatrolTransportType
        $totalTransportType = MstRbm::selectRaw('COUNT(DISTINCT patrol_transport_type) as total_transport_type')
            ->whereNotNull('patrol_transport_type') // Abaikan null
            ->where('patrol_transport_type', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_transport_type;

        $listTransportType = MstRbm::selectRaw('patrol_transport_type, COUNT(*) as total')
            ->whereNotNull('patrol_transport_type') // Abaikan null
            ->where('patrol_transport_type', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('patrol_transport_type')
            ->orderBy('total', 'desc')
            ->get();

        // 8. Hitung jumlah berdasarkan totalObservationCategory_0
        $totalObservationCategory_0 = MstRbm::selectRaw('COUNT(DISTINCT observation_category_0) as total_observation_category_0')
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_observation_category_0;

        $listObservationCategory_0 = MstRbm::selectRaw('observation_category_0, COUNT(*) as total')
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('observation_category_0')
            ->orderBy('total', 'desc')
            ->get();

        // 9. Hitung jumlah berdasarkan PatrolTransportType
        $totalObservationCategory_1 = MstRbm::selectRaw('COUNT(DISTINCT observation_category_1) as total_observation_category_1')
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_observation_category_1;

        $listObservationCategory_1 = MstRbm::selectRaw('observation_category_1, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('observation_category_1')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listBencana = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listBencana = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listInvasif = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        // 10. Hitung jumlah berdasarkan Tipe Temuan
        $totalTipeTemuan = MstRbm::selectRaw('COUNT(DISTINCT tipe_temuan) as total_tipe_temuan')
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_tipe_temuan;

        $listTipeTemuan = MstRbm::selectRaw('tipe_temuan, COUNT(*) as total')
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('tipe_temuan')
            ->orderBy('total', 'desc')
            ->get();

        // 11. Hitung jumlah berdasarkan Perlu Tindak Lanjut
        $totalPerluTindakLanjut = MstRbm::selectRaw('COUNT(DISTINCT perlu_tindak_lanjut) as total_perlu_tindak_lanjut')
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_perlu_tindak_lanjut;

        $listPerluTindakLanjut = MstRbm::selectRaw('perlu_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('perlu_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        // 12. Hitung jumlah berdasarkan status_tindak_lanjut
        $totalStatusTindakLanjut = MstRbm::selectRaw('COUNT(DISTINCT status_tindak_lanjut) as total_status_tindak_lanjut')
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_status_tindak_lanjut;

        $listStatusTindakLanjut = MstRbm::selectRaw('status_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('status_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        // 13. Hitung jumlah berdasarkan tindakan
        $totalTindakan = MstRbm::selectRaw('COUNT(DISTINCT tindakan) as total_tindakan')
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_tindakan;

        $listTindakan = MstRbm::selectRaw('tindakan, COUNT(*) as total')
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('tindakan')
            ->orderBy('total', 'desc')
            ->get();

        // 14. Hitung jumlah berdasarkan jenis tumbuhan
        $totalTumbuhan = MstRbm::selectRaw('COUNT(DISTINCT jenis_tumbuhan) as total_jenis_tumbuhan')
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_jenis_tumbuhan;

        $listTumbuhan = MstRbm::selectRaw('jenis_tumbuhan, COUNT(*) as total')
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('jenis_tumbuhan')
            ->orderBy('total', 'desc')
            ->get();

        // 15. Hitung jumlah berdasarkan jenis satwa
        $totalSatwa = MstRbm::selectRaw('COUNT(DISTINCT jenis_satwa) as total_jenis_satwa')
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_jenis_satwa;

        $listSatwa = MstRbm::selectRaw('jenis_satwa, COUNT(*) as total')
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('jenis_satwa')
            ->orderBy('total', 'desc')
            ->get();

        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'totalPatroli' => $totalPatroli,
                'totalTitik' => $totalTitik,
                'totalStation' => $totalStation,
                'totalType' => $totalType,
                'totalTeam' => $totalTeam,
                'totalMandate' => $totalMandate,
                'totalLeader' => $totalLeader,
                'totalTransportType' => $totalTransportType,
                'totalObservationCategory_0' => $totalObservationCategory_0,
                'totalObservationCategory_1' => $totalObservationCategory_1,
                'totalTipeTemuan' => $totalTipeTemuan,
                'listStation' => $listStation,
                'listType' => $listType,
                'listTeam' => $listTeam,
                'listMandate' => $listMandate,
                'listLeader' => $listLeader,
                'listTransportType' => $listTransportType,
                'listObservationCategory_0' => $listObservationCategory_0,
                'listObservationCategory_1' => $listObservationCategory_1,
                'listTipeTemuan' => $listTipeTemuan,
                'totalPerluTindakLanjut' => $totalPerluTindakLanjut,
                'listPerluTindakLanjut' => $listPerluTindakLanjut,
                'totalStatusTindakLanjut' => $totalStatusTindakLanjut,
                'listStatusTindakLanjut' => $listStatusTindakLanjut,
                'totalTindakan' => $totalTindakan,
                'listTindakan' => $listTindakan,
                'totalTumbuhan' => $totalTumbuhan,
                'listTumbuhan' => $listTumbuhan,
                'totalSatwa' => $totalSatwa,
                'listSatwa' => $listSatwa,
                'listBencana' => $listBencana
            ]
        ]);
    }

    public function index()
    {
        $datas = MstRbm::select(
            'patrol_id',
            'year',
            'station',
            'leader',
            DB::raw('MIN(patrol_start_date) as patrol_start_date'),
            DB::raw('MAX(patrol_end_date) as patrol_end_date'),
            DB::raw('COUNT(*) as patrol_count'),
            DB::raw('DATEDIFF(MAX(patrol_end_date), MIN(patrol_start_date)) + 1 as total_patrol_duration') // Menghitung durasi berdasarkan tanggal awal dan akhir
        )
            ->groupBy('patrol_id', 'year', 'station', 'leader')
            ->when(request()->search, function ($query) {
                $query->where('patrol_id', 'like', '%' . request()->search . '%');
            })
            ->orderByDesc('year')
            ->paginate(10);

        $datas->appends(['search' => request()->search]);

        return new RbmResource(true, 'List Data', $datas);
    }

    public function maps(Request $request)
    {
        // Ambil parameter dari request
        $year = $request->input('year');
        $station = $request->input('station'); // Pastikan hanya satu input untuk 'station'
        $observation0 = $request->input('observation_category_0');
        $observation1 = $request->input('observation_category_1');
        $jenisSatwa = $request->input('jenis_satwa');
        $jenisTumbuhan = $request->input('jenis_tumbuhan');
        $type = $request->input('type');
        $mandate = $request->input('mandate');
        $leader = $request->input('leader');
        $patrolTransportType = $request->input('patrol_transport_type');
        $tindakan = $request->input('tindakan');
        $perluTindakLanjut = $request->input('perlu_tindak_lanjut');
        $statusTindakLanjut = $request->input('status_tindak_lanjut');
        $tipeTemuan = $request->input('tipe_temuan');

        // Cek apakah ada parameter request
        if ($request->hasAny([
            'year',
            'station',
            'observation_category_0',
            'observation_category_1',
            'jenis_satwa',
            'jenis_tumbuhan',
            'type',
            'mandate',
            'leader',
            'patrol_transport_type',
            'tindakan',
            'perlu_tindak_lanjut',
            'status_tindak_lanjut',
            'tipe_temuan'
        ])) {
            // Query data patroli dengan filter dinamis
            $patroliData = MstRbm::select(
                'year',
                'patrol_id',
                'type',
                'patrol_start_date',
                'patrol_end_date',
                'station',
                'team',
                'objective',
                'mandate',
                'patrol_leg_id',
                'leader',
                'patrol_transport_type',
                'waypoint_id',
                'waypoint_date',
                'waypoint_time',
                'last_modified',
                'last_modified_by',
                'observation_category_0',
                'observation_category_1',
                'jenis_tumbuhan',
                'kesesuaian_regulasi',
                'keterangan',
                'kondisi_tumbuhan',
                'perlu_tindak_lanjut',
                'status_tindak_lanjut',
                'tanggal_tindak_lanjut',
                'tindakan',
                'tipe_temuan',
                'umur_satwa',
                'usia_temuan',
                'geometry',
                'date',
                'patrol_start_date_2',
                'patrol_end_date_2',
                'patrol_duration',
                'jenis_satwa'
            )
                ->when($year, fn($query) => $query->where('year', 'like', "%$year%"))
                ->when($station, fn($query) => $query->where('station', 'like', "%$station%"))
                ->when($type, fn($query) => $query->where('type', 'like', "%$type%"))
                ->when($mandate, fn($query) => $query->where('mandate', 'like', "%$mandate%"))
                ->when($leader, fn($query) => $query->where('leader', 'like', "%$leader%"))
                ->when($patrolTransportType, fn($query) => $query->where('patrol_transport_type', 'like', "%$patrolTransportType%"))
                ->when($observation0, fn($query) => $query->where('observation_category_0', 'like', "%$observation0%"))
                ->when($observation1, fn($query) => $query->where('observation_category_1', 'like', "%$observation1%"))
                ->when($jenisSatwa, fn($query) => $query->where('jenis_satwa', 'like', "%$jenisSatwa%"))
                ->when($jenisTumbuhan, fn($query) => $query->where('jenis_tumbuhan', 'like', "%$jenisTumbuhan%"))
                ->when($tindakan, fn($query) => $query->where('tindakan', 'like', "%$tindakan%"))
                ->when($perluTindakLanjut, fn($query) => $query->where('perlu_tindak_lanjut', 'like', "%$perluTindakLanjut%"))
                ->when($statusTindakLanjut, fn($query) => $query->where('status_tindak_lanjut', 'like', "%$statusTindakLanjut%"))
                ->when($tipeTemuan, fn($query) => $query->where('tipe_temuan', 'like', "%$tipeTemuan%"))
                ->limit('2000')
                ->get();
        } else {
            $patroliData = MstRbm::select(
                'year',
                'patrol_id',
                'type',
                'patrol_start_date',
                'patrol_end_date',
                'station',
                'team',
                'objective',
                'mandate',
                'patrol_leg_id',
                'leader',
                'patrol_transport_type',
                'waypoint_id',
                'waypoint_date',
                'waypoint_time',
                'last_modified',
                'last_modified_by',
                'observation_category_0',
                'observation_category_1',
                'jenis_tumbuhan',
                'kesesuaian_regulasi',
                'keterangan',
                'kondisi_tumbuhan',
                'perlu_tindak_lanjut',
                'status_tindak_lanjut',
                'tanggal_tindak_lanjut',
                'tindakan',
                'tipe_temuan',
                'umur_satwa',
                'usia_temuan',
                'geometry',
                'date',
                'patrol_start_date_2',
                'patrol_end_date_2',
                'patrol_duration',
                'jenis_satwa'
            )

                ->where('observation_category_0', 'Aktivitas Manusia')

                ->limit('2000')
                ->get();
        }

        // Cek apakah data kosong
        if ($patroliData->isEmpty()) {
            return new RbmResource(false, 'Data patroli tidak ditemukan', []);
        }

        // Return data dalam format RbmResource
        return new RbmResource(true, 'Data patroli ditemukan', $patroliData);
    }

    public function showByPatroliId($patroli_id, $year)
    {
        // Ambil semua data berdasarkan patroli_id dan year
        $patroliData = MstRbm::where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->orderBy('waypoint_date', 'asc')
            ->orderBy('waypoint_time', 'asc')
            ->get();

        if ($patroliData->isEmpty()) {
            return new RbmResource(false, 'Data patroli tidak ditemukan', []);
        }

        // Ambil data pertama untuk mendapatkan informasi umum
        $firstPatroli = $patroliData->first();

        // Hitung durasi patroli (dalam hari)
        $durasiPatroli = null;
        if ($firstPatroli->patrol_start_date && $firstPatroli->patrol_end_date) {
            $startDate = new \DateTime($firstPatroli->patrol_start_date);
            $endDate = new \DateTime($firstPatroli->patrol_end_date);
            $durasiPatroli = $startDate->diff($endDate)->days + 1;
        }

        // Format data hasil
        $result = [
            'patrol_id' => $firstPatroli->patrol_id,
            'year' => $firstPatroli->year,
            'station' => $firstPatroli->station,
            'leader' => $firstPatroli->leader,
            'patroli_start' => $firstPatroli->patrol_start_date,
            'patroli_end' => $firstPatroli->patrol_end_date,
            'center' => $firstPatroli->geometry,
            'durasi_patroli' => $durasiPatroli,
            'jumlah_patrol' => $patroliData->count(),
            'detail_patrol' => $patroliData, // Semua data patroli berdasarkan patrol_id dan year

        ];

        // Return hasil menggunakan RbmResource
        return new RbmResource(true, 'Data Patroli ditemukan', $result);
    }

    public function summaryByPatroliId($patroli_id, $year)
    {
        $listObservationCategory_0 = MstRbm::selectRaw('observation_category_0, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->groupBy('observation_category_0')
            ->orderBy('total', 'desc')
            ->get();
        $listObservationCategory_1 = MstRbm::selectRaw('observation_category_1, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->groupBy('observation_category_1')
            ->orderBy('total', 'desc')
            ->get();

        $listTipeTemuan = MstRbm::selectRaw('tipe_temuan, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->groupBy('tipe_temuan')
            ->orderBy('total', 'desc')
            ->get();

        $listPerluTindakLanjut = MstRbm::selectRaw('perlu_tindak_lanjut, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->groupBy('perlu_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        $listStatusTindakLanjut = MstRbm::selectRaw('status_tindak_lanjut, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->groupBy('status_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        $listTindakan = MstRbm::selectRaw('tindakan, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->groupBy('tindakan')
            ->orderBy('total', 'desc')
            ->get();

        $listTumbuhan = MstRbm::selectRaw('jenis_tumbuhan, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->groupBy('jenis_tumbuhan')
            ->orderBy('total', 'desc')
            ->get();

        $listSatwa = MstRbm::selectRaw('jenis_satwa, COUNT(*) as total')
            ->where('patrol_id', $patroli_id)
            ->where('year', $year)
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->groupBy('jenis_satwa')
            ->orderBy('total', 'desc')
            ->get();


        // Format data hasil
        $result = [
            'listObservationCategory_0'  => $listObservationCategory_0,
            'listObservationCategory_1' => $listObservationCategory_1,
            'listTipeTemuan' => $listTipeTemuan,
            'listPerluTindakLanjut' => $listPerluTindakLanjut,
            'listStatusTindakLanjut' => $listStatusTindakLanjut,
            'listTindakan' => $listTindakan,
            'listTumbuhan' => $listTumbuhan,
            'listSatwa' => $listSatwa,
        ];

        // Return hasil menggunakan RbmResource
        return new RbmResource(true, 'Data Patroli ditemukan', $result);
    }

    public function paramFilter()
    {
        $year = MstRbm::selectRaw('year, COUNT(*) as total')
            ->whereNotNull('year') // Abaikan null
            ->where('year', '!=', '') // Abaikan string kosong
            ->groupBy('year')
            ->orderBy('total', 'desc')

            ->get();
        $listStation = MstRbm::selectRaw('station, COUNT(*) as total')
            ->whereNotNull('station') // Abaikan null
            ->where('station', '!=', '') // Abaikan string kosong
            ->groupBy('station')
            ->orderBy('total', 'desc')

            ->get();
        $listLeader = MstRbm::selectRaw('leader, COUNT(*) as total')
            ->whereNotNull('leader') // Abaikan null
            ->where('leader', '!=', '') // Abaikan string kosong
            ->groupBy('leader')
            ->orderBy('total', 'desc')

            ->get();
        $listObservationCategory_0 = MstRbm::selectRaw('observation_category_0, COUNT(*) as total')
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->groupBy('observation_category_0')
            ->orderBy('total', 'desc')

            ->get();
        $listObservationCategory_1 = MstRbm::selectRaw('observation_category_1, COUNT(*) as total')
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->groupBy('observation_category_1')
            ->orderBy('total', 'desc')

            ->get();

        $listTipeTemuan = MstRbm::selectRaw('tipe_temuan, COUNT(*) as total')
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->groupBy('tipe_temuan')
            ->orderBy('total', 'desc')

            ->get();

        $listPerluTindakLanjut = MstRbm::selectRaw('perlu_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->groupBy('perlu_tindak_lanjut')
            ->orderBy('total', 'desc')

            ->get();

        $listStatusTindakLanjut = MstRbm::selectRaw('status_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->groupBy('status_tindak_lanjut')
            ->orderBy('total', 'desc')

            ->get();

        $listTindakan = MstRbm::selectRaw('tindakan, COUNT(*) as total')
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->groupBy('tindakan')
            ->orderBy('total', 'desc')

            ->get();

        $listTumbuhan = MstRbm::selectRaw('jenis_tumbuhan, COUNT(*) as total')
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->groupBy('jenis_tumbuhan')
            ->orderBy('total', 'desc')

            ->get();

        $listSatwa = MstRbm::selectRaw('jenis_satwa, COUNT(*) as total')
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->groupBy('jenis_satwa')
            ->orderBy('total', 'desc')

            ->get();


        // Format data hasil
        $result = [
            'year' => $year,
            'listStation' => $listStation,
            'listLeader' => $listLeader,
            'listObservationCategory_0'  => $listObservationCategory_0,
            'listObservationCategory_1' => $listObservationCategory_1,
            'listTipeTemuan' => $listTipeTemuan,
            'listPerluTindakLanjut' => $listPerluTindakLanjut,
            'listStatusTindakLanjut' => $listStatusTindakLanjut,
            'listTindakan' => $listTindakan,
            'listTumbuhan' => $listTumbuhan,
            'listSatwa' => $listSatwa,
        ];

        // Return hasil menggunakan RbmResource
        return new RbmResource(true, 'Data filter ditemukan', $result);
    }



    public function summaryByStation(Request $request, $station)
    {
        $year = $request->input('year');
        // 1. Hitung jumlah berdasarkan PATROLI_ID
        $totalPatroli = MstRbm::selectRaw('COUNT(DISTINCT patrol_id, year) as total_patroli')
            ->whereNotNull('patrol_id') // Abaikan null
            ->where('patrol_id', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_patroli;

        $totalTitik = MstRbm::whereNotNull('patrol_id')
            ->where('patrol_id', '!=', '')
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->count();

        // 2. Hitung jumlah berdasarkan STATION
        $totalStation = MstRbm::selectRaw('COUNT(DISTINCT station) as total_station')
            ->whereNotNull('station') // Abaikan null
            ->where('station', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_station;

        $listStation = MstRbm::selectRaw('station, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('station') // Abaikan null
            ->where('station', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('station')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Hitung jumlah berdasarkan Type
        $totalType = MstRbm::selectRaw('COUNT(DISTINCT type) as total_type')
            ->whereNotNull('type') // Abaikan null
            ->where('type', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_type;

        $listType = MstRbm::selectRaw('type, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('type') // Abaikan null
            ->where('type', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get();

        // 4. Hitung jumlah berdasarkan Team
        $totalTeam = MstRbm::selectRaw('COUNT(DISTINCT team) as total_team')
            ->whereNotNull('team') // Abaikan null
            ->where('team', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_team;

        $listTeam = MstRbm::selectRaw('team, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('team') // Abaikan null
            ->where('team', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('team')
            ->orderBy('total', 'desc')
            ->get();

        // 5. Hitung jumlah berdasarkan Mandate
        $totalMandate = MstRbm::selectRaw('COUNT(DISTINCT mandate) as total_mandate')
            ->whereNotNull('mandate') // Abaikan null
            ->where('mandate', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_mandate;

        $listMandate = MstRbm::selectRaw('mandate, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('mandate') // Abaikan null
            ->where('mandate', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })

            ->groupBy('mandate')
            ->orderBy('total', 'desc')
            ->get();

        // 6. Hitung jumlah berdasarkan Leader
        $totalLeader = MstRbm::selectRaw('COUNT(DISTINCT leader) as total_leader')
            ->whereNotNull('leader') // Abaikan null
            ->where('leader', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_leader;

        $listLeader = MstRbm::selectRaw('leader, COUNT(DISTINCT patrol_id, year) as total')
            ->whereNotNull('leader') // Abaikan null
            ->where('leader', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('leader') // Group berdasarkan leader
            ->orderBy('total', 'desc') // Urutkan berdasarkan total secara descending
            ->get();

        // 7. Hitung jumlah berdasarkan PatrolTransportType
        $totalTransportType = MstRbm::selectRaw('COUNT(DISTINCT patrol_transport_type) as total_transport_type')
            ->whereNotNull('patrol_transport_type') // Abaikan null
            ->where('patrol_transport_type', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_transport_type;

        $listTransportType = MstRbm::selectRaw('patrol_transport_type, COUNT(*) as total')
            ->whereNotNull('patrol_transport_type') // Abaikan null
            ->where('patrol_transport_type', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('patrol_transport_type')
            ->orderBy('total', 'desc')
            ->get();

        // 8. Hitung jumlah berdasarkan totalObservationCategory_0
        $totalObservationCategory_0 = MstRbm::selectRaw('COUNT(DISTINCT observation_category_0) as total_observation_category_0')
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_observation_category_0;

        $listObservationCategory_0 = MstRbm::selectRaw('observation_category_0, COUNT(*) as total')
            ->whereNotNull('observation_category_0') // Abaikan null
            ->where('observation_category_0', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('observation_category_0')
            ->orderBy('total', 'desc')
            ->get();

        // 9. Hitung jumlah berdasarkan PatrolTransportType
        $totalObservationCategory_1 = MstRbm::selectRaw('COUNT(DISTINCT observation_category_1) as total_observation_category_1')
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_observation_category_1;

        $listObservationCategory_1 = MstRbm::selectRaw('observation_category_1, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('observation_category_1') // Abaikan null
            ->where('observation_category_1', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('observation_category_1')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listBencana = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listBencana = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        $listInvasif = MstRbm::selectRaw('keterangan, MAX(observation_category_0) as observation_category_0, COUNT(*) as total')
            ->whereNotNull('keterangan') // Abaikan null
            ->where('keterangan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('keterangan')
            ->orderBy('observation_category_0', 'asc')
            ->orderBy('total', 'desc')
            ->get();

        // 10. Hitung jumlah berdasarkan Tipe Temuan
        $totalTipeTemuan = MstRbm::selectRaw('COUNT(DISTINCT tipe_temuan) as total_tipe_temuan')
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_tipe_temuan;

        $listTipeTemuan = MstRbm::selectRaw('tipe_temuan, COUNT(*) as total')
            ->whereNotNull('tipe_temuan') // Abaikan null
            ->where('tipe_temuan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('tipe_temuan')
            ->orderBy('total', 'desc')
            ->get();

        // 11. Hitung jumlah berdasarkan Perlu Tindak Lanjut
        $totalPerluTindakLanjut = MstRbm::selectRaw('COUNT(DISTINCT perlu_tindak_lanjut) as total_perlu_tindak_lanjut')
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_perlu_tindak_lanjut;

        $listPerluTindakLanjut = MstRbm::selectRaw('perlu_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('perlu_tindak_lanjut') // Abaikan null
            ->where('perlu_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('perlu_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        // 12. Hitung jumlah berdasarkan status_tindak_lanjut
        $totalStatusTindakLanjut = MstRbm::selectRaw('COUNT(DISTINCT status_tindak_lanjut) as total_status_tindak_lanjut')
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_status_tindak_lanjut;

        $listStatusTindakLanjut = MstRbm::selectRaw('status_tindak_lanjut, COUNT(*) as total')
            ->whereNotNull('status_tindak_lanjut') // Abaikan null
            ->where('status_tindak_lanjut', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('status_tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        // 13. Hitung jumlah berdasarkan tindakan
        $totalTindakan = MstRbm::selectRaw('COUNT(DISTINCT tindakan) as total_tindakan')
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_tindakan;

        $listTindakan = MstRbm::selectRaw('tindakan, COUNT(*) as total')
            ->whereNotNull('tindakan') // Abaikan null
            ->where('tindakan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('tindakan')
            ->orderBy('total', 'desc')
            ->get();

        // 14. Hitung jumlah berdasarkan jenis tumbuhan
        $totalTumbuhan = MstRbm::selectRaw('COUNT(DISTINCT jenis_tumbuhan) as total_jenis_tumbuhan')
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_jenis_tumbuhan;

        $listTumbuhan = MstRbm::selectRaw('jenis_tumbuhan, COUNT(*) as total')
            ->whereNotNull('jenis_tumbuhan') // Abaikan null
            ->where('jenis_tumbuhan', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('jenis_tumbuhan')
            ->orderBy('total', 'desc')
            ->get();

        // 15. Hitung jumlah berdasarkan jenis satwa
        $totalSatwa = MstRbm::selectRaw('COUNT(DISTINCT jenis_satwa) as total_jenis_satwa')
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->get()
            ->first()
            ->total_jenis_satwa;

        $listSatwa = MstRbm::selectRaw('jenis_satwa, COUNT(*) as total')
            ->whereNotNull('jenis_satwa') // Abaikan null
            ->where('jenis_satwa', '!=', '') // Abaikan string kosong
            ->where('station', $station)
            ->when($year, function ($query, $year) {
                return $query->where('year', $year);
            })
            ->groupBy('jenis_satwa')
            ->orderBy('total', 'desc')
            ->get();

        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'totalPatroli' => $totalPatroli,
                'totalTitik' => $totalTitik,
                'totalStation' => $totalStation,
                'totalType' => $totalType,
                'totalTeam' => $totalTeam,
                'totalMandate' => $totalMandate,
                'totalLeader' => $totalLeader,
                'totalTransportType' => $totalTransportType,
                'totalObservationCategory_0' => $totalObservationCategory_0,
                'totalObservationCategory_1' => $totalObservationCategory_1,
                'totalTipeTemuan' => $totalTipeTemuan,
                'listStation' => $listStation,
                'listType' => $listType,
                'listTeam' => $listTeam,
                'listMandate' => $listMandate,
                'listLeader' => $listLeader,
                'listTransportType' => $listTransportType,
                'listObservationCategory_0' => $listObservationCategory_0,
                'listObservationCategory_1' => $listObservationCategory_1,
                'listTipeTemuan' => $listTipeTemuan,
                'totalPerluTindakLanjut' => $totalPerluTindakLanjut,
                'listPerluTindakLanjut' => $listPerluTindakLanjut,
                'totalStatusTindakLanjut' => $totalStatusTindakLanjut,
                'listStatusTindakLanjut' => $listStatusTindakLanjut,
                'totalTindakan' => $totalTindakan,
                'listTindakan' => $listTindakan,
                'totalTumbuhan' => $totalTumbuhan,
                'listTumbuhan' => $listTumbuhan,
                'totalSatwa' => $totalSatwa,
                'listSatwa' => $listSatwa,
                'listBencana' => $listBencana
            ]
        ]);

        // Return hasil menggunakan RbmResource
        return new RbmResource(true, 'Data Patroli ditemukan', $result);
    }
}
