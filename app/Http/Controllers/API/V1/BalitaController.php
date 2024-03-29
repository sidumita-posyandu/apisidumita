<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Balita;
use App\Keluarga;
use App\DetailKeluarga;
use App\OperatorPosyandu;
use App\PetugasKesehatan;
use Validator;
use DB;

class BalitaController extends Controller
{
    public function index() 
    {
        $balitas = Balita::with(['detail_keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
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
                'message' => "Data balita gagal ditambahkan"
            ]);
        }

        $balita = Balita::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data balita berhasil ditambahkan",
            'data' => $balita
        ], 200);
    }

    public function show($id) 
    {
        $balitas = Balita::with(['detail_keluarga'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ]);

    }

    public function showBalitaForPetugas(){
        $desa_id =  PetugasKesehatan::where("user_id", auth()->user()->id)->first()->dusun->desa_id;
        // dd($desa_id);

        $balitas = DB::table('tb_balita')
        ->select('*','tb_balita.id')
        ->join('tb_detail_keluarga', 'tb_detail_keluarga.id', '=', 'tb_balita.detail_keluarga_id')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->where("m_dusun.desa_id",'=', $desa_id)
        ->where('tb_balita.deleted_at', '=', null)
        ->where('tb_detail_keluarga.deleted_at', '=', null)
        ->groupBy('tb_balita.id')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ]);

    }

    public function showBalitaForOperator(){
        $kecamatan_id =  OperatorPosyandu::where("user_id", auth()->user()->id)->first()->kecamatan_id;
        // dd($desa_id);

        $balitas = DB::table('tb_balita')
        ->select('*','tb_balita.id')
        ->join('tb_detail_keluarga', 'tb_detail_keluarga.id', '=', 'tb_balita.detail_keluarga_id')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("m_desa.kecamatan_id",'=', $kecamatan_id)
        ->where('tb_balita.deleted_at', '=', null)
        ->where('tb_detail_keluarga.deleted_at', '=', null)
        ->groupBy('tb_balita.id')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ]);

    }


    public function showMyBalitas() 
    {
        $data = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $det_keluarga = DetailKeluarga::where('keluarga_id', $data)->get();
        $id = [];
        foreach ($det_keluarga as $key => $value) {
            array_push($id,$value->id);
        }
        $balitas = Balita::with('detail_keluarga')->whereIn('detail_keluarga_id', $id)->get();
        // dd($balitas);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $balitas
        ]);
    }

    public function update(Request $request, Balita $balita)
    {
        $validasi = Validator::make($request->all(), [
            'nama_balia' => 'required',
            'tanggal_lahir' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'golongan_darah' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data balita gagal ditambahkan"
            ]);
        }
        
        $balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data balita berhasil diubah",
            'data' => $balita
        ], 200);
    }

    public function destroy($id)
    {
        $balita = Balita::findOrFail($id);
        $balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data balita berhasil dihapus!",
        ], 200);
    }
}