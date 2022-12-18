<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPemeriksaanBalitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pemeriksaan_balita', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pemeriksaan');
            $table->float('lingkar_kepala');
            $table->float('lingkar_lengan');
            $table->float('tinggi_badan');
            $table->float('berat_badan');
            $table->text('penanganan');
            $table->text('keluhan');
            $table->text('catatan');
            $table->unsignedBigInteger('balita_id');
            $table->unsignedBigInteger('petugas_kesehatan_id');
            $table->unsignedBigInteger('dokter_id');
            $table->foreign('petugas_kesehatan_id')->references('id')->on('tb_petugas_kesehatan');
            $table->foreign('balita_id')->references('id')->on('tb_balita');
            $table->foreign('dokter_id')->references('id')->on('tb_dokter');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pemeriksaan_balita');
    }
}