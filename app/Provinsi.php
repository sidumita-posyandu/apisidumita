<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'm_provinsi';

    protected $fillable = [
        'nama_provinsi'
    ];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
    }
}