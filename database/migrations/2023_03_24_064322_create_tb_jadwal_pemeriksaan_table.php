<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbJadwalPemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_jadwal_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pemeriksaan');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_berakhir');
            $table->unsignedBigInteger('operator_posyandu_id');
            $table->unsignedBigInteger('dusun_id');
            $table->foreign('operator_posyandu_id')->references('id')->on('tb_operator_posyandu');
            $table->foreign('dusun_id')->references('id')->on('m_dusun');
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
        Schema::dropIfExists('tb_jadwal_pemeriksaan');
    }
}