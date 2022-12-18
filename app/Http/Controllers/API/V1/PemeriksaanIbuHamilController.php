<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemeriksaanIbuHamil;
use Validator;

class PemeriksaanIbuHamilController extends Controller
{
    public function index() 
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::with('ibu_hamil')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_perut' => 'required',
            'denyut_nadi' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'ibu_hamil_id' => 'required|exists:tb_ibu_hamil,id',
            'petugas_kesehatan_id' => 'required|tb_petugas_kesehatan,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $pemeriksaan_ibu_hamil = PemeriksaanIbuHamil::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $pemeriksaan_ibu_hamil
        ], 200);
    }

    public function show($id) 
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function update(Request $request, PemeriksaanIbuHamil $pemeriksaan_ibu_hamil)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_perut' => 'required',
            'denyut_nadi' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'ibu_hamil_id' => 'required|exists:tb_ibu_hamil,id',
            'petugas_kesehatan_id' => 'required|tb_petugas_kesehatan,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $pemeriksaan_ibu_hamil->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $pemeriksaan_ibu_hamil
        ], 200);
    }

    public function destroy(PemeriksaanIbuHamil $pemeriksaan_ibu_hamil)
    {
        $pemeriksaan_ibu_hamil->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}