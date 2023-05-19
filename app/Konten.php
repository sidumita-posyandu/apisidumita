<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    protected $table = 'tb_konten';

    protected $fillable = [
        'judul','konten','gambar'
    ];
}