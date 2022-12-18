<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalPemeriksaan extends Model
{
    protected $table = 'tb_jadwal_pemeriksaan';

    protected $fillable = [
        'jenis_pemeriksaan', 'waktu_mulai', 'waktu_berakhir', 'keluarga_id', 'operator_posyandu_id', 'dusun_id'
    ];

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }
    
    public function operator_posyandu()
    {
        return $this->belongsTo(OperatorPosyandu::class, 'operator_posyandu_id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
}