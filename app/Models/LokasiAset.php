<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LokasiAset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'keterangan',
        'penanggung_jawab',
        'nip_penanggung_jawab',
        'kuasa_pengguna',
        'nip_kuasa_pengguna',
    ];

    public function aset()
    {
        return $this->hasMany(Aset::class);
    }
}
