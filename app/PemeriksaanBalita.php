<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanBalita extends Model
{
    protected $table = 'tb_pemeriksaan_balita';

    protected $fillable = [
        'tanggal_pemeriksaan','lingkar_kepala', 'lingkar_lengan', 'umur_balita', 'tinggi_badan','berat_badan',
        'penanganan', 'keluhan', 'catatan', 'balita_id', 'petugas_kesehatan_id','dokter_id','vitamin_id'
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'balita_id');
    }

    public function petugas_kesehatan()
    {
        return $this->belongsTo(PetugasKesehatan::class, 'petugas_kesehatan_id');
    }

    public function vitamin()
    {
        return $this->belongsTo(Vitamin::class, 'vitamin_id');
    }
    
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function detail_keluargas()
    {
        return $this->hasManyThrough(DetailKeluarga::class, Balita::class, 'detail_keluarga_id', 'id','balita_id');
    }

    public function detail_pemeriksaan_balita()
    {
        return $this->hasMany(DetailPemeriksaanBalita::class, 'pemeriksaan_balita_id');
    }

    public function vaksin()
    {
        return $this->belongsToMany(Vaksin::class, 'tb_detail_pemeriksaan_balita');
    }

    protected $casts = [
        'balita_id' => 'integer',
        'petugas_kesehatan_id' => 'integer',
        'dokter_id' => 'integer',
        'vitamin_id' => 'integer'
    ];
}