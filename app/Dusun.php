<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    protected $table = 'm_dusun';

    protected $fillable = [
        'nama_dusun', 'desa_id'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class, 'dusun_id');
    }

    public function petugaskesehatans()
    {
        return $this->hasMany(PetugasKesehatan::class, 'dusun_id');
    }

    protected $casts = [
        'desa_id' => 'integer',
    ];
}