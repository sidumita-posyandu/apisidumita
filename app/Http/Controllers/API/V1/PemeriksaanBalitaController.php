<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PemeriksaanBalita;
use App\DetailKeluarga;
use Validator;

class PemeriksaanBalitaController extends Controller
{
    public function index() 
    {
        $pemeriksaan_balitas = PemeriksaanBalita::with(['balita', 'petugas_kesehatan', 'dokter'])->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_kepala' => 'required',
            'lingkar_lengan' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'balita_id' => 'required|exists:tb_balita,id',
            'petugas_kesehatan_id' => 'required|exists:tb_petugas_kesehatan,id',
            'penanganan' => 'required',
            'catatan' => 'required',
            'keluhan' => 'required',
            'dokter_id' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $pemeriksaan_balita = PemeriksaanBalita::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $pemeriksaan_balita
        ], 200);
    }

    public function show($id) 
    {
        $pemeriksaan_balitas = PemeriksaanBalita::findOrFail($id)->with(['balita'])->first();;

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function getPemeriksaanByBalita($id)
    {
        $pemeriksaan_balitas = PemeriksaanBalita::where('balita_id', $id)->orderBy('tanggal_pemeriksaan','desc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function getDetailPemeriksaanByBalita($id){
        $pemeriksaan_balitas = PemeriksaanBalita::where('balita_id', $id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);
    }

    public function getTwoLastPemeriksaanByBalita($id)
    {
        $pemeriksaan_balitas = PemeriksaanBalita::where('balita_id', $id)->orderBy('tanggal_pemeriksaan','desc')->limit(2)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function update(Request $request, PemeriksaanBalita $pemeriksaan_balita)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_kepala' => 'required',
            'lingkar_lengan' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'balita_id' => 'required|exists:tb_balita,id',
            'petugas_kesehatan_id' => 'required|tb_petugas_kesehatan,id',
            'bulan_imunisasi_id' => 'required|exists:tb_bulan_imunisasi.id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $pemeriksaan_balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $pemeriksaan_balita
        ], 200);
    }

    public function destroy(PemeriksaanBalita $pemeriksaan_balita)
    {
        $pemeriksaan_balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}