<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PemeriksaanIbuHamil;
use Validator;
use DB;

class PemeriksaanIbuHamilController extends Controller
{
    public function index()
    {
        $pemeriksaan_ibu_hamils = DB::table('tb_detail_keluarga')
        ->select('*')
        ->join('tb_ibu_hamil', 'tb_ibu_hamil.detail_keluarga_id', '=', 'tb_detail_keluarga.id')
        ->join('tb_pemeriksaan_ibu_hamil', 'tb_pemeriksaan_ibu_hamil.ibu_hamil_id', '=', 'tb_ibu_hamil.id')
        ->get();

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
            'penanganan' => 'required',
            'keluhan' => 'required',
            'catatan' => 'required',
            'ibu_hamil_id' => 'required|exists:tb_ibu_hamil,id',
            'petugas_kesehatan_id' => 'required|exists:tb_petugas_kesehatan,id',
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
        $pemeriksaan_ibu_hamil = PemeriksaanIbuHamil::with(['ibu_hamil', 'petugas_kesehatan'])->findOrFail($id);

        $detail_keluarga = $pemeriksaan_ibu_hamil->ibu_hamil()->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => array_merge(json_decode($pemeriksaan_ibu_hamil, true),json_decode($detail_keluarga['detail_keluarga'], true))
        ]);
    }

    public function getPemeriksaanByIbuHamil($id)
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::where('ibu_hamil_id', $id)->orderBy('tanggal_pemeriksaan','desc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function getTwoLastPemeriksaanByIbuHamil($id)
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::where('ibu_hamil_id', $id)->orderBy('tanggal_pemeriksaan','desc')->limit(2)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }
}