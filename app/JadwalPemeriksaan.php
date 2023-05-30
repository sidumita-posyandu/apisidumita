<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalPemeriksaan extends Model
{
    protected $table = 'tb_jadwal_pemeriksaan';

    protected $fillable = [
        'jenis_pemeriksaan', 'waktu_mulai', 'waktu_berakhir', 'operator_posyandu_id', 'dusun_id'
    ];

    
    public function operator_posyandu()
    {
        return $this->belongsTo(OperatorPosyandu::class, 'operator_posyandu_id');
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    protected $casts = [
        'dusun_id' => 'integer',
        'operator_posyandu_id' => 'integer',
    ];
}