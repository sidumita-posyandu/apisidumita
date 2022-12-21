<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DetailPemeriksaanBalita;
use Validator;

class DetailPemeriksaanBalitaController extends Controller
{
    public function index() 
    {
        $detail_pemeriksaan_balita = DetailPemeriksaanBalita::with('pemeriksaan_balita', 'balita','vaksin')->get();

        dd($detail_pemeriksaan_balita);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_pemeriksaan_balita
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'pemeriksaan_balita_id' => 'required|exists:tb_pemeriksaan_balita,id',
            'vaksin_id' => 'required|exists:tb_vaksin,id',
            'balita_id' => 'required|exists:tb_balita,id',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }
        
        $detail_pemeriksaan_balita = DetailPemeriksaanBalita::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $detail_pemeriksaan_balita
        ], 200);
    }

    public function show($id) 
    {
        $detail_pemeriksaan_balita = DetailPemeriksaanBalita::findOrFail($id)->with('pemeriksaan_balita', 'balita','vaksin')->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_pemeriksaan_balita
        ]);

    }

    public function update(Request $request, DetailPemeriksaanBalita $detail_pemeriksaan_balita)
    {
        $validasi = Validator::make($request->all(), [
            'pemeriksaan_balita_id' => 'required|exists:tb_pemeriksaan_balita,id',
            'vaksin_id' => 'required|exists:tb_vaksin,id',
            'balita_id' => 'required|exists:tb_balita,id',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }
        
        $detail_pemeriksaan_balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $detail_pemeriksaan_balita
        ], 200);
    }

    public function destroy(DetailPemeriksaanBalita $detail_pemeriksaan_balita)
    {
        $detail_pemeriksaan_balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}