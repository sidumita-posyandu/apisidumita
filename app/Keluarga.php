<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'tb_keluarga';

    protected $fillable = [
        'no_kartu_keluarga', 'kepala_keluarga', 'alamat', 'jumlah', 'dusun_id', 'user_id'
    ];

    protected $dates = ['deleted_at'];

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail_keluargas()
    {
        return $this->hasMany(DetailKeluarga::class, 'keluarga_id');
    }

    protected $appends = [
        'jumlah_keluarga'
    ];

    public function getJumlahKeluargaAttribute()
    {
        return $this->detail_keluargas()->count();
    }

    protected $casts = [
        'dusun_id' => 'integer',
        'user_id' => 'integer'
    ];
}