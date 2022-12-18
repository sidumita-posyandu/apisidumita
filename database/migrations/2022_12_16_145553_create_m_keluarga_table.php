<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kartu_keluarga');
            $table->string('kepala_keluarga');
            $table->string('alamat');
            $table->string('jumlah');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dusun_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('m_keluarga');
    }
}