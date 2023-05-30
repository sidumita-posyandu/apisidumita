<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'm_desa';

    protected $fillable = [
        'nama_desa', 'kecamatan_id'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function dusuns()
    {
        return $this->hasMany(Dusun::class, 'desa_id');
    }

    protected $casts = [
        'kecamatan_id' => 'integer',
    ];
}