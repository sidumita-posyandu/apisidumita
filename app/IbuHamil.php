<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IbuHamil extends Model
{
    use SoftDeletes;
    
    protected $table = 'tb_ibu_hamil';

    protected $fillable = [
        'detail_keluarga_id', 'berat_badan_prakehamilan', 'tinggi_badan_prakehamilan', 'tanggal_prakehamilan', 'kehamilan_ke'
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
        // 'berat_badan_prakehamilan' => 'double',
        // 'tinggi_badan_prakehamilan' => 'double',
    ];
}