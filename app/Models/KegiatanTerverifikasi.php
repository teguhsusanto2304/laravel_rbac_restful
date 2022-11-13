<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTerverifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_kegiatan',
        'lokasi_kegiatan',
        'unsur_id',
        'klasifikasi_id',
        'metode_id',
        'tingkat_id',
        'created_by'
    ];
}
