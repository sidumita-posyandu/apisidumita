<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IbuHamil extends Model
{
    protected $table = 'tb_ibu_hamil';

    protected $fillable = [
        'detail_keluarga_id', 'berat_badan_prakehamilan', 'tinggi_badan_prakehamilan'
    ];

    public function detail_keluarga()
    {
        return $this->belongsTo(DetailKeluarga::class, 'detail_keluarga_id');
    }

    public function pemeriksaan_ibu_hamils()
    {
        return $this->hasMany(PemeriksaanIbuHamil::class);
    }

    protected $casts = [
        'detail_keluarga_id' => 'integer',
        'berat_badan_prakehamilan' => 'double',
        'tinggi_badan_prakehamilan' => 'double',
    ];
}