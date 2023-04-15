<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dusun;
use App\Keluarga;
use App\User;
use App\DetailKeluarga;
use Validator;

class KeluargaController extends Controller
{
    public function index() 
    {
        $keluargas = Keluarga::with(['dusun', 'user', 'detail_keluargas'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluargas
        ]);
    }

    public function show($id)
    {
        $keluarga = Keluarga::with(['dusun', 'user', 'detail_keluargas'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluarga
        ]);
    }

    public function showMyKeluarga()
    {
        $keluarga = Keluarga::with(['dusun', 'user', 'detail_keluargas'])->where("user_id", auth()->user()->id)->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluarga
        ]);
    }

   

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'no_kartu_keluarga' => 'required|unique:tb_keluarga',
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'jumlah' => 'required',
            'dusun_id' => 'required|exists:m_dusun,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $keluarga = Keluarga::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data keluarga berhasil ditambahkan",
            'data' => $keluarga
        ], 200);
    }

    public function storeMyKeluarga(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'no_kartu_keluarga' => 'required|unique:tb_keluarga',
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'dusun_id' => 'required|exists:m_dusun,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $data = $request->all();
        $data['user_id'] = User::where("id", auth()->user()->id)->first()->id;
        $keluarga = Keluarga::create($data);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data keluarga berhasil ditambahkan",
            'data' => $keluarga
        ], 200); 
    }

    public function update(Request $request, Keluarga $keluarga)
    {
        $validasi = Validator::make($request->all(), [
            'no_kartu_keluarga' => 'required',
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'jumlah' => 'required',
            'dusun_id' => 'required|exists:m_dusun,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $keluarga->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data keluarga berhasil diubah",
            'data' => $keluarga
        ], 200);
    }

    // public function updateMyKeluarga(Request $request, Keluarga $keluarga)
    // {
    //     $validasi = Validator::make($request->all(), [
    //         'no_kartu_keluarga' => 'required',
    //         'kepala_keluarga' => 'required',
    //         'alamat' => 'required',
    //         'dusun_id' => 'required|exists:m_dusun,id',
    //     ]);
    
    //     if($validasi->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'code' => 400,
    //             'message' => "Data tidak dapat ditambahkan"
    //         ], 400);
    //     }
        
    //     $data = $request->all();
    //     $data['user_id'] = User::where("id", auth()->user()->id)->first()->id;
    //     $keluarga->update($data);

    //     return response()->json([
    //         'status' => true,
    //         'code' => 200,
    //         'message' => "Data keluarga berhasil diubah",
    //         'data' => $keluarga
    //     ], 200);
    // }

    public function destroy(Keluarga $keluarga)
    {
        $keluarga->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data keluarga berhasil dihapus!",
        ], 200);
    }
}