<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbImunisasiBalitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_imunisasi_balita', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balita_id');
            $table->unsignedBigInteger('petugas_kesehatan_id');
            $table->foreign('petugas_kesehatan_id')->references('id')->on('tb_petugas_kesehatan');
            $table->foreign('balita_id')->references('id')->on('tb_balita');
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
        Schema::dropIfExists('tb_imunisasi_balita');
    }
}