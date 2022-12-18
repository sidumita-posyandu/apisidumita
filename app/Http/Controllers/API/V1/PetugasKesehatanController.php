<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasKesehatanController extends Controller
{
    public function index()
    {
        $petugas_kesehatan = auth()->user()->petugas_kesehatan;
 
        return response()->json([
            'success' => true,
            'data' => $petugas_kesehatan
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required', 
            'jenis_kelamin' => 'required', 
            'tempat_lahir' => 'required', 
            'tanggal_lahir' => 'required', 
            'no_telp' => 'required', 
            'email' => 'required', 
            'nik' => 'required', 
        ]);
 
        $petugas_kesehatan = new OperatorPuskesmas();
        $petugas_kesehatan->nama = $request->nama;
        $petugas_kesehatan->jenis_kelamin = $request->jenis_kelamin;
        $petugas_kesehatan->tempat_lahir = $request->tempat_lahir;
        $petugas_kesehatan->tanggal_lahir = $request->tanggal_lahir;
        $petugas_kesehatan->no_telp = $request->no_telp;
        $petugas_kesehatan->email = $request->email;
        $petugas_kesehatan->nik = $request->nik;
 
        if (auth()->user()->petugas_kesehatanes()->save($petugas_kesehatan))
            return response()->json([
                'success' => true,
                'data' => $petugas_kesehatan->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'petugas kesehatan belum ditambahkan'
            ], 500);
    }
}