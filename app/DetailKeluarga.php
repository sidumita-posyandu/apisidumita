<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailKeluarga extends Model
{
    protected $table = 'tb_detail_keluarga';

    protected $fillable = [
        'nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama',
        'pendidikan', 'no_telp', 'golongan_darah', 'jenis_pekerjaan', 'status_perkawinan',
        'status_dalam_keluarga', 'kewarganegaraan', 'keluarga_id'
    ];

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }

    public function balitas()
    {
        return $this->hasMany(Balita::class, 'balita_id');
    }
}