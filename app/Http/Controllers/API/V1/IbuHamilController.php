<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\IbuHamil;
use App\Keluarga;
use App\DetailKeluarga;
use App\PetugasKesehatan;
use App\DB;
use Validator;

class IbuHamilController extends Controller
{
    public function index() 
    {
        $ibu_hamils = IbuHamil::with(['detail_keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ], 200);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'detail_keluarga_id'
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }

        $ibu_hamil = IbuHamil::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data ibu hamil berhasil ditambahkan",
            'data' => $ibu_hamil
        ], 200);
    }

    public function show($id) 
    {
        $ibu_hamils = IbuHamil::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ]);

    }

    public function showIbuHamilForPetugas(){
        $desa_id =  PetugasKesehatan::where("user_id", auth()->user()->id)->first()->dusun->desa_id;
        // dd($petugas_kesehatan);

        $ibu_hamils = DB::table('tb_ibu_hamil')
        ->select('*')
        ->join('tb_detail_keluarga', 'tb_detail_keluarga.id', '=', 'tb_ibu_hamil.detail_keluarga_id')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->where("m_dusun.desa_id",$desa_id)
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ]);

    }

    public function showMyIbuHamils() 
    {
        $data = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $det_keluarga = DetailKeluarga::where('keluarga_id', $data)->get();
        $id = [];
        foreach ($det_keluarga as $key => $value) {
            array_push($id,$value->id);
        }
        $ibu_hamils = IbuHamil::with('detail_keluarga')->whereIn('detail_keluarga_id', $id)->get();
        // dd($balitas);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamils
        ]);
    }

    public function update(Request $request, $id)
    {
        $validasi = Validator::make($request->all(), [
            'berat_badan_prakehamilan',
            'tinggi_badan_prakehamilan',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data gagal ditambahkan"
            ]);
        }

        $ibu_hamil = IbuHamil::findOrFail($id);

        $ibu_hamil->update($request->all());


        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data ibu hamil berhasil diubah",
            'data' => $ibu_hamil
        ], 200);
    }

    public function destroy(IbuHamil $ibu_hamil)
    {
        $ibu_hamil->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data ibu hamil berhasil dihapus!",
        ], 200);
    }
}