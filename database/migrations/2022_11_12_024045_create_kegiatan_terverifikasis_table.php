<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanTerverifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_terverifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan',100);
            $table->string('lokasi_kegiatan',100);
            $table->string('unsur_id',100);
            $table->string('klasifikasi_id',100);
            $table->string('metode_id',3);
            $table->string('tingkat_id',3);
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
        Schema::dropIfExists('kegiatan_terverifikasis');
    }
}
