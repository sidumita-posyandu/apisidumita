<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImunisasiBalita extends Model
{
    protected $table = 'tb_imunisasi_balita';

    protected $fillable = [
        'pemeriksaan_balita_id', 'vaksin_id', 'vitamin_id'
    ];

    public function pemeriksaan_balita()
    {
        return $this->belongsTo(PemeriksaanBalita::class, 'pemeriksaan_balita_id');
    }

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class, 'vaksin_id');
    }

    public function vitamin()
    {
        return $this->belongsTo(Vitamin::class, 'vitamin_id');
    }
}