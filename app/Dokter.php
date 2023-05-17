<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'tb_dokter';

    protected $fillable = [
        'nip', 'nama_dokter', 'no_telp', 'alamat', 'dusun_id'
    ];

    public function dokters()
    {
        return $this->hasMany(PemeriksaanBalita::class);
    }

    public function dusun(){
        return $this->belongsTo(Dusun::class);
    }
}
