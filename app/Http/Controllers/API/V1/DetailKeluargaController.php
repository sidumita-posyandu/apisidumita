<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DetailKeluarga;
use App\Keluarga;
use Validator;

class DetailKeluargaController extends Controller
{
    public function index() 
    {
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:tb_detail_keluarga',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
            'keluarga_id' => 'required|exists:tb_keluarga,id'
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $detail_keluarga = DetailKeluarga::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail keluarga berhasil ditambahkan",
            'data' => $detail_keluarga
        ], 200);
    }

    public function update(Request $request, DetailKeluarga $detail_keluarga)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
            'keluarga_id' => 'required|exists:m_keluarga'
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $detail_keluarga->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail_keluarga berhasil diubah",
            'data' => $detail_keluarga
        ], 200);
    }

    public function destroy(DetailKeluarga $detail_keluarga)
    {
        $detail_keluarga->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail_keluarga berhasil dihapus!",
        ], 200);
    }

    //test relasi
    public function getKeluarga($keluarga_id)
    {
        $keluarga =  Keluarga::find($keluarga_id);
        return response()->json([
            'data' => $keluarga
        ], 200);
    }
}