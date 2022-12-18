<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaksin extends Model
{
    protected $table = 'tb_vaksin';

    protected $fillable = [
        'nama_vaksin','dosis','catatan','status'
    ];

    public function imunisasi_balitas()
    {
        return $this->hasMany(ImunisasiBalita::class);
    }
}
