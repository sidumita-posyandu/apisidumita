<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::with(['role'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $user
        ]);
    }

    // public function peserta(Request $request){
    //     $peserta = User::with(['pesertas'])->where('role_id', '=', 4)->get();

    //     return response()->json([
    //         'status' => true,
    //         'code' => 200,
    //         'data' => $peserta
    //     ]);
    // }

    public function validasi($id)
    {
        $user = User::with(['role'])->find($id);

        if($user == null)
        {
            return response()->json([
                'status' => false,
                'code' => 404,
                'data' => 'Data tidak ditemukan'
            ]);
            
        }else{
            $user->isValid = 1;
            $user->save();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => 'Data berhasil diupdate'
            ]);
        }
    }

    public function unvalidasi($id)
    {
        $user = User::with(['role'])->find($id);

        if($user == null)
        {
            return response()->json([
                'status' => false,
                'code' => 404,
                'data' => 'Data tidak ditemukan'
            ]);
            
        }else{
            $user->isValid = 0;
            $user->save();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => 'Data berhasil diupdate'
            ]);
        }
    }
}