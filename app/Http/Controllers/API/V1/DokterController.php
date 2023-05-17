<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DOkter;
use Validator;

class DokterController extends Controller
{
    public function index() 
    {
        $dokters = Dokter::with(['dusun'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $dokters
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nip' => 'required|unique:tb_dokter',
            'nama_dokter' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
            'dusun_id' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $dokter = Dokter::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dokter berhasil ditambahkan",
            'data' => $dokter
        ], 200);
    }

    public function show($id) 
    {
        $dokters = Dokter::with(['dusun'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $dokters
        ]);

    }

    public function update(Request $request, Dokter $dokter)
    {
        $validasi = Validator::make($request->all(), [
            'nip' => 'required',
            'nama_dokter' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
            'dusun_id' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $dokter->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dokter berhasil diubah",
            'data' => $dokter
        ], 200);
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dokter berhasil dihapus!",
        ], 200);
    }
}
