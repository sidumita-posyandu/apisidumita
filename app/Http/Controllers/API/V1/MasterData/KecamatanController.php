<?php

namespace App\Http\Controllers\API\V1\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kecamatan;
use App\Kabupaten;

class KecamatanController extends Controller
{
    public function index() 
    {
        $kecamatans = Kecamatan::with(['kabupaten'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kecamatans
        ]);
    }

    public function store(Request $request)
    {
        $kecamatan = Kecamatan::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kecamatan berhasil ditambahkan",
            'data' => $kecamatan
        ], 200);
    }

    public function show($id) 
    {
        $kecamatan = Kecamatan::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kecamatan
        ]);

    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $kecamatan->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kecamatan berhasil diubah",
            'data' => $kecamatan
        ], 200);
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data kecamatan berhasil dihapus!",
        ], 200);
    }

    public function fetchKabupaten(Request $request)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $request->kabupaten_id)->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kecamatans
        ]);
    }
}