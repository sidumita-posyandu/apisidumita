<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanIbuHamil extends Model
{
    protected $table = 'tb_pemeriksaan_ibu_hamil';

    protected $fillable =[
        'tanggal_pemeriksaan','tinggi_badan', 'lingkar_perut', 'berat_badan', 'denyut_nadi',
        'keluhan', 'penanganan', 'catatan', 'ibu_hamil_id', 'petugas_kesehatan_id'
    ];

    public function ibu_hamil()
    {
        return $this->belongsTo(IbuHamil::class, 'ibu_hamil_id');
    }

    public function petugas_kesehatan()
    {
        return $this->belongsTo(PetugasKesehatan::class, 'petugas_kesehatan_id');
    }
}