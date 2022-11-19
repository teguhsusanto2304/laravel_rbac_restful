<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTerverifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_kegiatan',
        'kode',
        'lokasi_kegiatan',
        'unsur_id',
        'klasifikasi_id',
        'metode_id',
        'penyelenggara_id',
        'tingkat_id',
        'created_by'
    ];
    public function peserta(){
        return $this->hasMany(PesertaKegiatanTerverifikasi::class);
    }
}
