<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanBalita extends Model
{
    protected $table = 'tb_pemeriksaan_balita';

    protected $fillable = [
        'tanggal_pemeriksaan','lingkar_kepala', 'lingkar_lengan', 'tinggi_badan','berat_badan',
        'penanganan', 'keluhan', 'catatan', 'balita_id', 'petugas_kesehatan_id','dokter_id'
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'balita_id');
    }

    public function petugas_kesehatan()
    {
        return $this->belongsTo(PetugasKesehatan::class, 'petugas_kesehatan_id');
    }
    
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function imunisasi_balita()
    {
        return $this->hasOne(ImunisasiBalita::class);
    }
}