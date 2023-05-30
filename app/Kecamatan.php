<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'm_kecamatan';

    protected $fillable = [
        'nama_kecamatan', 'kabupaten_id'
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function desas()
    {
        return $this->hasMany(Desa::class, 'kecamatan_id');
    }

    protected $casts = [
        'kabupaten_id' => 'integer',
    ];
}