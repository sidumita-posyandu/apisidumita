<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPemeriksaanIbuHamilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pemeriksaan_ibu_hamil', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pemeriksaan');
            $table->float('tinggi_badan');
            $table->float('berat_badan');
            $table->float('lingkar_perut');
            $table->float('denyut_nadi');
            $table->text('penanganan');
            $table->text('keluhan');
            $table->text('catatan');
            $table->unsignedBigInteger('ibu_hamil_id');
            $table->unsignedBigInteger('petugas_kesehatan_id');
            $table->foreign('petugas_kesehatan_id')->references('id')->on('tb_petugas_kesehatan');
            $table->foreign('ibu_hamil_id')->references('id')->on('tb_ibu_hamil');
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
        Schema::dropIfExists('tb_pemeriksaan_ibu_hamil');
    }
}