<?php

namespace App\Http\Controllers\API\V1\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Desa;
use App\Kecamatan;
use Validator;

class DesaController extends Controller
{
    public function index() 
    {
        $desas = Desa::with(['kecamatan'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $desas
        ]);
    }

    public function show($id) 
    {
        $desa = Desa::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $desa
        ]);
    }

    public function store(Request $request)
    {
        $desa = Desa::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data desa berhasil ditambahkan",
            'data' => $desa
        ], 200);
    }

    public function update(Request $request, Desa $desa)
    {
        $desa->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data desa berhasil diubah",
            'data' => $desa
        ], 200);
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data desa berhasil dihapus!",
        ], 200);
    }
}