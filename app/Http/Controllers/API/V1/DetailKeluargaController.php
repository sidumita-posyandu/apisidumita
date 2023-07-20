<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DetailKeluarga;
use App\Keluarga;
use App\Balita;
use App\IbuHamil;
use App\OperatorPosyandu;
use App\PetugasKesehatan;
use Validator;
use Carbon\Carbon;
use DB;

class DetailKeluargaController extends Controller
{
    public function index() 
    {
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function show($id) 
    {
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where('id', $id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function showMyDetailKeluarga() 
    {
        $data['keluarga_id'] = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where("keluarga_id", $data)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function showMyBalitas()
    {
        $data['keluarga_id'] = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where("status_dalam_keluarga","Anak")->where("keluarga_id", $data)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function showMyIbuHamils()
    {
        $data['keluarga_id'] = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where("status_dalam_keluarga","Istri")->where("keluarga_id", $data)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function DetailKeluargaByOperator(){

        $kecamatan_id =  OperatorPosyandu::where("user_id", auth()->user()->id)->first()->kecamatan_id;

        $keluargas = DB::table('tb_detail_keluarga')
        ->select('tb_detail_keluarga.*')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("m_desa.kecamatan_id",'=', $kecamatan_id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluargas
        ]);
    }

    public function DetailKeluargaByPetugas(){
        $desa_id =  PetugasKesehatan::where("user_id", auth()->user()->id)->first()->dusun->desa_id;

        $keluargas = DB::table('tb_detail_keluarga')
        ->select('tb_detail_keluarga.*')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("m_dusun.desa_id",'=', $desa_id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluargas
        ]);
    }

    public function ShowDetailKeluargabyIdKeluarga($id){
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where("keluarga_id",$id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $detail_keluargas
        ]);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:tb_detail_keluarga',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
            'keluarga_id' => 'required|exists:tb_keluarga,id'
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $data = $request->all();
        $detail_keluarga = DetailKeluarga::create($data);

        if ($detail_keluarga->status_dalam_keluarga == "Anak"){
            $balitas = Balita::create([
                'detail_keluarga_id' => $detail_keluarga->id
            ]);
        } else if($detail_keluarga->status_dalam_keluarga == "Istri"){
            $ibu_hamil = IbuHamil::create([
                'detail_keluarga_id' => $detail_keluarga->id
            ]);
        }
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail keluarga berhasil ditambahkan",
            'data' => $detail_keluarga
        ], 200);
    }

    public function storeMyDetKeluarga(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:tb_detail_keluarga',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        $data = $request->all();
        $data['keluarga_id'] = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $detail_keluarga = DetailKeluarga::create($data);

        if ($detail_keluarga->status_dalam_keluarga == "Anak"){
        $balitas = Balita::create([
            'detail_keluarga_id' => $detail_keluarga->id
        ]);
        } else if($detail_keluarga->status_dalam_keluarga == "Istri"){
            $ibu_hamil = IbuHamil::create([
                'detail_keluarga_id' => $detail_keluarga->id
            ]);
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail keluarga berhasil ditambahkan",
            'data' => $detail_keluarga
        ], 200);
    }

    public function update(Request $request, DetailKeluarga $detail_keluarga)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
            'keluarga_id' => 'required|exists:m_keluarga'
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $detail_keluarga->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail_keluarga berhasil diubah",
            'data' => $detail_keluarga
        ], 200);
    }

    public function updateMyDetKeluarga(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'no_telp' => 'required',
            'golongan_darah' => 'required',
            'jenis_pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'status_dalam_keluarga' => 'required',
            'kewarganegaraan' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $data = $request->all();
        $data['keluarga_id'] = Keluarga::where("user_id", auth()->user()->id)->first()->id;
        $detail_keluarga = DetailKeluarga::where('keluarga_id', $data['keluarga_id'])->where('id',request('id'))->first();
        // dd($detail_keluarga);
        $detail_keluarga->update([
            'nama_lengkap' => request('nama_lengkap'),
            'nik' => request('nik'),
            'jenis_kelamin' => request('jenis_kelamin'),
            'tempat_lahir' => request('tempat_lahir'),
            'tanggal_lahir' => request('tanggal_lahir'),
            'agama' => request('agama'),
            'pendidikan' => request('pendidikan'),
            'no_telp' => request('no_telp'),
            'golongan_darah' => request('golongan_darah'),
            'jenis_pekerjaan' => request('jenis_pekerjaan'),
            'status_perkawinan' => request('status_perkawinan'),
            'status_dalam_keluarga' => request('status_dalam_keluarga'),
            'kewarganegaraan' => request('kewarganegaraan'),
        ]);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail_keluarga berhasil diubah",
            'data' => $detail_keluarga
        ], 200);
    }


    public function destroy(DetailKeluarga $detail_keluarga)
    {
        // dd($detail_keluarga);
        $detail_keluarga->delete();
        //cascade with delete balita and ibu hamil (sekaligus menghapus balita dan ibu hamil pada tabelnya)

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data detail_keluarga berhasil dihapus!",
        ], 200);
    }

    //test relasi
    public function getKeluarga($keluarga_id)
    {
        $keluarga =  Keluarga::find($keluarga_id);
        return response()->json([
            'data' => $keluarga
        ], 200);
    }

    //umur
    public function getUmur($id)
    {
        $orang = DetailKeluarga::where('id', $id)->first();
        $now = Carbon::now();
        $birthday = Carbon::parse($orang->tanggal_lahir);
        $umur = $birthday->diffInYears($now);
        $umurbulan = $birthday->diffInMonths($now);
        $status = "tahun";

        if($umur == 0){
            $umur = $birthday->diffInMonths($now);
            $status = "bulan";
            if($umur == 0){
                $umur = $birthday->diffInWeeks($now);
                $status = "minggu";
                if($umur == 0){
                    $umur = $birthday->diffInDays($now);
                    $status = "hari";
                }
            }
        }
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'umur' => $umur,
                'format' => $status,
                'usia_bulan' => $umurbulan,
            ]
        ]);
    }

    //revisi (menampilkan ibu hamil darid detail keluarga dengan status istri)

    public function IbuHamilByAdmin() 
    {
        $ibu_hamil = DB::table('tb_detail_keluarga')
        ->select('tb_detail_keluarga.*')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("tb_detail_keluarga.status_dalam_keluarga",'=', 'istri')
        ->where("tb_detail_keluarga.deleted_at",'=', null)
        ->orderBy('tb_detail_keluarga.created_at', 'desc')
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamil
        ]);
    }

    public function IbuHamilByOperator(){

        $kecamatan_id =  OperatorPosyandu::where("user_id", auth()->user()->id)->first()->kecamatan_id;

        $keluargas = DB::table('tb_detail_keluarga')
        ->select('tb_detail_keluarga.*')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("tb_detail_keluarga.status_dalam_keluarga",'=', 'istri')
        ->where("tb_detail_keluarga.deleted_at",'=', null)
        ->where("m_desa.kecamatan_id",'=', $kecamatan_id)
        ->orderBy('tb_detail_keluarga.created_at', 'desc')
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluargas
        ]);
    }

    public function IbuHamilByPetugas(){
        $desa_id =  PetugasKesehatan::where("user_id", auth()->user()->id)->first()->dusun->desa_id;

        $keluargas = DB::table('tb_detail_keluarga')
        ->select('tb_detail_keluarga.*')
        ->join('tb_keluarga', 'tb_keluarga.id', '=', 'tb_detail_keluarga.keluarga_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where("tb_detail_keluarga.status_dalam_keluarga",'=', 'istri')
        ->where("tb_detail_keluarga.deleted_at",'=', null)
        ->where("m_dusun.desa_id",'=', $desa_id)
        ->orderBy('tb_detail_keluarga.created_at', 'desc')
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $keluargas
        ]);
    }

    public function ShowIbuHamil($id)
    {
        $ibu_hamil = DetailKeluarga::with('ibu_hamils')->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $ibu_hamil
        ]);
    }
}