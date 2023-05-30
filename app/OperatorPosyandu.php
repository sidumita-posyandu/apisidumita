<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperatorPosyandu extends Model
{
    protected $table = 'tb_operator_posyandu';

    protected $fillable = [
        'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'email', 'nik', 'user_id', 'kecamatan_id'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal_pemeriksaans()
    {
        return $this->hasMany(JadwalPemeriksaan::class);
    }

    protected $casts = [
        'user_id' => 'integer',
        'kecamatan_id' => 'integer',
        
    ];
}