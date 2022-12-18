<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Balita;
use Validator;

class BalitaController extends Controller
{
    public function index() 
    {
        $balitas = Balita::with(['detail_keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ], 200);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'detail_keluarga_id'
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data balita gagal ditambahkan"
            ]);
        }

        $balita = Balita::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data balita berhasil ditambahkan",
            'data' => $balita
        ], 200);
    }

    public function show($id) 
    {
        $balitas = Balita::findOrFail($id)->with(['detail_keluarga'])->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ]);

    }

    public function update(Request $request, Balita $balita)
    {
        $validasi = Validator::make($request->all(), [
            'nama_balia' => 'required',
            'tanggal_lahir' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'golongan_darah' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data balita gagal ditambahkan"
            ]);
        }
        
        $balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $balita
        ], 200);
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}