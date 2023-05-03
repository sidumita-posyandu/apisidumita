<?php

namespace App\Http\Controllers\API\V1\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Provinsi;

class ProvinsiController extends Controller
{
    public function index() 
    {
        $provinsis = Provinsi::all();
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $provinsis
        ]);
    }

    public function store(Request $request)
    {
        $provinsi = Provinsi::create($request->all());
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data provinsi berhasil ditambahkan",
            'data' => $provinsi
        ], 200);
    }

    public function show($id) 
    {
        $provinsis = Provinsi::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $provinsis
        ]);
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        $provinsi->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data provinsi berhasil diubah",
            'data' => $provinsi
        ], 200);
    }

    public function destroy(Provinsi $provinsi)
    {
        $provinsi->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data provinsi berhasil dihapus!",
        ], 200);
    }

    
}