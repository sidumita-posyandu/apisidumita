<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetugasKesehatan extends Model
{
    protected $table = 'tb_petugas_kesehatan';

    protected $fillable = [
        'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'email', 'nik', 'user_id', 'dusun_id'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    public function dusun()
    {
        return $this->hasOne(Dusun::class, 'id','dusun_id');
    }


}