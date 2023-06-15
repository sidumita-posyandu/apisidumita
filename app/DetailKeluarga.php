<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailKeluarga extends Model
{
    use SoftDeletes;

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

    public function ibu_hamils()
    {
        return $this->hasMany(IbuHamil::class, 'ibu_hamil_id');
    }

    protected $casts = [
        'keluarga_id' => 'integer',
    ];
}