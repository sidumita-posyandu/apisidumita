<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balita extends Model
{
    use SoftDeletes;

    protected $table = 'tb_balita';

    protected $fillable = [
        'detail_keluarga_id'
    ];

    public function detail_keluarga()
    {
        return $this->belongsTo(DetailKeluarga::class, 'detail_keluarga_id');
    }

    public function pemeriksaan_balitas()
    {
        return $this->hasMany(PemeriksaanBalita::class);
    }

    protected $casts = [
        'detail_keluarga_id' => 'integer',
    ];
}