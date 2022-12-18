<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetailKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detail_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('no_telp');
            $table->string('golongan_darah');
            $table->string('jenis_pekerjaan');
            $table->string('status_perkawinan');
            $table->string('status_dalam_keluarga');
            $table->string('kewarganegaraan');
            $table->unsignedBigInteger('keluarga_id');
            $table->foreign('keluarga_id')->references('id')->on('m_keluarga');
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
        Schema::dropIfExists('tb_detail_keluarga');
    }
}