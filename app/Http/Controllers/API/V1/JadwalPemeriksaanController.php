<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JadwalPemeriksaan;
use Validator;

class JadwalPemeriksaanController extends Controller
{
    public function index() 
    {
        $jadwal_pemeriksaan = JadwalPemeriksaan::with(['keluarga', 'operator_posyandu', 'dusun'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $jadwal_pemeriksaan
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'jenis_pemeriksaan' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'keluarga_id' => 'required|exists:m_keluarga,id',
            'operator_posyandu_id' => 'required|exists:tb_operator_posyandu,id',
            'dusun_id' => 'required|exists:m_dusun,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $jadwal_pemeriksaan = JadwalPemeriksaan::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $jadwal_pemeriksaan
        ], 200);
    }

    public function show($id) 
    {
        $jadwal_pemeriksaan = JadwalPemeriksaan::findOrFail($id)->with(['keluarga', 'operator_posyandu', 'dusun'])->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $jadwal_pemeriksaan
        ]);

    }

    public function update(Request $request, $id)
    {
        $validasi = Validator::make($request->all(), [
            'jenis_pemeriksaan' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'keluarga_id' => 'required|exists:m_keluarga,id',
            'operator_posyandu_id' => 'required|exists:tb_operator_posyandu,id',
            'dusun_id' => 'required|exists:m_dusun,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $jadwal_pemeriksaan = JadwalPemeriksaan::find($id);
        $jadwal_pemeriksaan->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data jadwal berhasil diubah",
            'data' => $jadwal_pemeriksaan
        ], 200);
    }

    public function destroy(JadwalPemeriksaan $jadwal_pemeriksaan)
    {
        $jadwal_pemeriksaan->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}