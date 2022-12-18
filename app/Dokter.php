<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'tb_dokter';

    protected $fillable = [
        'nidn', 'nama_dokter'
    ];

    public function dokters()
    {
        return $this->hasMany(PemeriksaanBalita::class);
    }
}
