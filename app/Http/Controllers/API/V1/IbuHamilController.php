<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IbuHamil;
use Validator;

class IbuHamilController extends Controller
{
    public function index() 
    {
        $ibu_hamils = IbuHamil::with(['detail_keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ], 200);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => 'required',
            'nik' => 'required',
            'alamat_domisili' => 'required',
            'alamat_rumah' => 'required',
            'usia_kandungan' => 'required',
            'no_telp' => 'required',
            'agama' => 'required',
            'golongan_darah' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }

        $ibu_hamil = IbuHamil::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data ibu_hamil berhasil ditambahkan",
            'data' => $ibu_hamil
        ], 200);
    }

    public function show($id) 
    {
        $ibu_hamils = IbuHamil::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ]);

    }

    public function update(Request $request, IbuHamil $ibu_hamil)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => 'required',
            'nik' => 'required',
            'alamat_domisili' => 'required',
            'alamat_rumah' => 'required',
            'usia_kandungan' => 'required',
            'no_telp' => 'required',
            'agama' => 'required',
            'golongan_darah' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }
        
        $ibu_hamil->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $ibu_hamil
        ], 200);
    }

    public function destroy(IbuHamil $ibu_hamil)
    {
        $ibu_hamil->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}