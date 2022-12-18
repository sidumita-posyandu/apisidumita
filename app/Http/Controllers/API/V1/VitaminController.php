<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vitamin;
use Validator;

class VitaminController extends Controller
{
    public function index() 
    {
        $vitamins = Vitamin::all();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $vitamins
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_vitamin' => 'required',
            'dosis' => 'required',
            'catatan' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $vitamin = Vitamin::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vitamin berhasil ditambahkan",
            'data' => $vitamin
        ], 200);
    }

    public function show($id) 
    {
        $vitamins = Vitamin::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $vitamins
        ]);

    }

    public function update(Request $request, Vitamin $vitamin)
    {
        $validasi = Validator::make($request->all(), [
            'nama_vitamin' => 'required',
            'dosis' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $vitamin->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vitamin berhasil diubah",
            'data' => $vitamin
        ], 200);
    }

    public function destroy(Vitamin $vitamin)
    {
        $vitamin->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vitamin berhasil dihapus!",
        ], 200);
    }
}