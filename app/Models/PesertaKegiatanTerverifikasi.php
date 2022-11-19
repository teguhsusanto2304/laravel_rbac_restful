<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaKegiatanTerverifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kegiatan_id',
        'nik',
        'nama_peserta',
        'klasifikasi_id',
        'metode_id',
        'unsur_id',
        'tingkat_id',
        'created_by'
    ];
}
