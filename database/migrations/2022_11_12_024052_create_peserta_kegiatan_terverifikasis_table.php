<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaKegiatanTerverifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta_kegiatan_terverifikasis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kegiatan_id');
            $table->string('nik',20);
            $table->string('klasifikasi_id',100);
            $table->string('metode_id',100);
            $table->string('unsur_id',50);
            $table->string('sifat_id',50);
            $table->string('tingkat_id',50);
            $table->Integer('data_status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peserta_kegiatan_terverifikasis');
    }
}
