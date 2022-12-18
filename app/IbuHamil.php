<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IbuHamil extends Model
{
    protected $table = 'tb_ibu_hamil';

    protected $fillable = [
        'detail_keluarga_id'
    ];

    public function detail_keluarga()
    {
        return $this->belongsTo(DetailKeluarga::class, 'detail_keluarga_id');
    }

    public function pemeriksaan_ibu_hamils()
    {
        return $this->hasMany(PemeriksaanIbuHamils::class);
    }
}