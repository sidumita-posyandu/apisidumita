<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vaksin;
use Validator;

class VaksinController extends Controller
{
    public function index() 
    {
        $vaksins = Vaksin::all();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $vaksins
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_vaksin' => 'required',
            'dosis' => 'required',
            'catatan' => 'required',
            'status' => 'required'
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $vaksin = Vaksin::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vaksin berhasil ditambahkan",
            'data' => $vaksin
        ], 200);
    }

    public function show($id) 
    {
        $vaksins = Vaksin::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $vaksins
        ]);

    }

    public function update(Request $request, vaksin $vaksin)
    {
        $validasi = Validator::make($request->all(), [
            'nama_vaksin' => 'required',
            'dosis' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $vaksin->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vaksin berhasil diubah",
            'data' => $vaksin
        ], 200);
    }

    public function destroy(vaksin $vaksin)
    {
        $vaksin->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vaksin berhasil dihapus!",
        ], 200);
    }
}