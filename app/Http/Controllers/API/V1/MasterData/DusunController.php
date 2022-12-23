<?php

namespace App\Http\Controllers\API\V1\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dusun;
use App\Desa;

class DusunController extends Controller
{
    public function index() 
    {
        $dusuns = Dusun::with(['desa'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $dusuns
        ]);

    }

    public function show($id) 
    {
        $dusun = Dusun::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $dusun
        ]);
    }

    public function store(Request $request)
    {
        $dusun = Dusun::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dusun berhasil ditambahkan",
            'data' => $dusun
        ], 200);
    }

    public function update(Request $request, Dusun $dusun)
    {
        $dusun->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dusun berhasil diubah",
            'data' => $dusun
        ], 200);
    }

    public function destroy(Dusun $dusun)
    {
        $dusun->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data dusun berhasil dihapus!",
        ], 200);
    }
}