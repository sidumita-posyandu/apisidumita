<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaksin extends Model
{
    protected $table = 'm_vaksin';

    protected $fillable = [
        'nama_vaksin','dosis','catatan','status', 'umur_rek_min', 'umur_rek_max', 'umur_min', 'umur_max', 'umur_susulan_min', 'umur_susulan_max'
    ];

    public function imunisasi_balitas()
    {
        return $this->hasMany(ImunisasiBalita::class);
    }
}
