<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OperatorPosyandu;
use Validator;

class OperatorPosyanduController extends Controller
{
    public function index()
    {
        // $operator_posyandu = OperatorPosyandu::where("user_id", auth()->user()->id);
        $operator_posyandu = auth()->user();

        return response()->json([
            'success' => true,
            'data' => $operator_posyandu
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required', 
            'jenis_kelamin' => 'required', 
            'tempat_lahir' => 'required', 
            'tanggal_lahir' => 'required', 
            'no_telp' => 'required', 
            'email' => 'required', 
            'nik' => 'required', 
        ]);

        $operator_posyandu = new OperatorPosyandu();
        $operator_posyandu->nama = $request->nama;
        $operator_posyandu->jenis_kelamin = $request->jenis_kelamin;
        $operator_posyandu->tempat_lahir = $request->tempat_lahir;
        $operator_posyandu->tanggal_lahir = $request->tanggal_lahir;
        $operator_posyandu->no_telp = $request->no_telp;
        $operator_posyandu->email = $request->email;
        $operator_posyandu->nik = $request->nik;
 
        if (auth()->user()->operator_posyandu()->save($operator_posyandu))
            return response()->json([
                'success' => true,
                'data' => $operator_posyandu->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'operator puskesmas belum ditambahkan'
            ], 500);
    }
}