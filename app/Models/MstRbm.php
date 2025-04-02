<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstRbm extends Model
{
    use HasFactory;

    protected $table = 'mst_rbm';

    // Semua kolom yang boleh diisi
    protected $fillable = [
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
        'jenis_satwa',
    ];
}