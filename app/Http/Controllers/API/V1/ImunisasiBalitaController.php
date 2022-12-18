<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ImunisasiBalita;
use Validator;

class ImunsasiBalitaController extends Controller
{
    public function index() 
    {
        $imunisasi_balita = ImunisasiBalita::with('pemeriksaan_balita')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $imunisasi_balita
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'pemeriksaan_balita_id' => 'required|exists:tb_imunisasi_balita,id',
            'vaksin_id' => 'required|exists:tb_vaksin,id',
            'vitamin_id' => 'required|exists:tb_vitamin,id',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }
        
        $imunisasi_balita = ImunisasiBalita::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $imunisasi_balita
        ], 200);
    }

    public function show($id) 
    {
        $imunisasi_balita = ImunisasiBalita::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $imunisasi_balita
        ]);

    }

    public function update(Request $request, ImunisasiBalita $imunisasi_balita)
    {
        $validasi = Validator::make($request->all(), [
            'pemeriksaan_balita_id' => 'required|exists:tb_imunisasi_balita,id',
            'vaksin_id' => 'required|exists:tb_vaksin,id',
            'vitamin_id' => 'required|exists:tb_vitamin,id',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }
        
        $imunisasi_balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $imunisasi_balita
        ], 200);
    }

    public function destroy(ImunisasiBalita $imunisasi_balita)
    {
        $imunisasi_balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}