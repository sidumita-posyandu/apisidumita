<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vitamin extends Model
{
    protected $table = 'm_vitamin';

    protected $fillable = [
        'nama_vitamin','dosis','catatan'
    ];

    public function imunisasi_balitas()
    {
        return $this->hasMany(ImunisasiBalita::class);
    }
}
