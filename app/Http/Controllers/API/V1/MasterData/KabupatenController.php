<?php

namespace App\Http\Controllers\API\V1\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kabupaten;
use App\Provinsi;

class KabupatenController extends Controller
{
    public function index() 
    {
        $kabupatens = Kabupaten::with(['provinsi'])->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kabupatens
        ]);
    }

    public function store(Request $request)
    {
        $kabupaten = Kabupaten::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kabupaten berhasil ditambahkan",
            'data' => $kabupaten
        ], 200);
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        $kabupaten->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kabupaten berhasil diubah",
            'data' => $kabupaten
        ], 200);
    }

    public function show($id) 
    {
        $kabupaten = Kabupaten::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kabupaten
        ]);

    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kabupaten berhasil dihapus!",
        ], 200);
    }

    public function fetchProvinsi($id)
    {
        $kabupatens = Kabupaten::with(['provinsi'])->where('provinsi_id', $id)->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kabupatens
        ]);
    }
}