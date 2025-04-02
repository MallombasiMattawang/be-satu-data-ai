<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWru extends Model
{
    use HasFactory;

    protected $table = 'mst_wru';

    // Semua kolom yang boleh diisi
    protected $fillable = [
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
    ];
}