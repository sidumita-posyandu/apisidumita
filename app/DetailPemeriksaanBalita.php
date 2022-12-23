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
        return $this->belongsTo(Balita::class, 'balita_id');
    }

    public function detail_keluargas()
    {
        return $this->hasManyThrough(DetailKeluarga::class, Balita::class, 'detail_keluarga_id', 'id','balita_id','id');
    }
}