<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPemeriksaanBalita extends Model
{
    protected $table = 'tb_detail_pemeriksaan_balita';

    protected $fillable = [
        'pemeriksaan_balita_id', 'balita_id','vaksin_id'
    ];

    public function pemeriksaan_balita()
    {
        return $this->belongsTo(PemeriksaanBalita::class, 'pemeriksaan_balita_id');
    }

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class, 'vaksin_id');
    }

    public function balita()
    {
        return $this->belongsTo(PemeriksaanBalita::class, 'pemeriksaan_balita_id');
    }
}