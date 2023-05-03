<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PetugasKesehatan;
use DB;
use Validator;

class PetugasKesehatanController extends Controller
{
    public function index()
    {
        $petugas_kesehatan = auth()->user();
 
        return response()->json([
            'success' => true,
            'data' => $petugas_kesehatan
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
 
        $petugas_kesehatan = new OperatorPuskesmas();
        $petugas_kesehatan->nama = $request->nama;
        $petugas_kesehatan->jenis_kelamin = $request->jenis_kelamin;
        $petugas_kesehatan->tempat_lahir = $request->tempat_lahir;
        $petugas_kesehatan->tanggal_lahir = $request->tanggal_lahir;
        $petugas_kesehatan->no_telp = $request->no_telp;
        $petugas_kesehatan->email = $request->email;
        $petugas_kesehatan->nik = $request->nik;
 
        if (auth()->user()->petugas_kesehatanes()->save($petugas_kesehatan))
            return response()->json([
                'success' => true,
                'data' => $petugas_kesehatan->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'petugas kesehatan belum ditambahkan'
            ], 500);
    }

    public function showMyPetugas(){

        // $data = PetugasKesehatan::where("user_id", auth()->user()->id)->first()->id;
        // $petugas_kesehatan = PetugasKesehatan::with(['user','dusun','desa'])
        // ->where("user_id", auth()->user()->id)->get();

        $petugas_kesehatan = DB::table('tb_petugas_kesehatan')
        ->select('*')
        // ->join('tb_petugas_kesehatan', 'tb_petugas_kesehatan.dusun_id', '=', 'm_dusun.id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_petugas_kesehatan.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->join('m_kecamatan', 'm_kecamatan.id', '=', 'm_desa.kecamatan_id')
        ->join('m_kabupaten', 'm_kabupaten.id', '=', 'm_kecamatan.kabupaten_id')
        ->join('m_provinsi', 'm_provinsi.id', '=', 'm_kabupaten.provinsi_id')
        // ->join('users', 'users.id', '=', 'tb_petugas_kesehatan.user_id')
        ->where("user_id", auth()->user()->id)->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $petugas_kesehatan
        ]);
    }

    public function updateMyPetugas(Request $request){
        $validasi = Validator::make($request->all(), [
            'nama' => 'required', 
            'jenis_kelamin' => 'required', 
            'tempat_lahir' => 'required', 
            'tanggal_lahir' => 'required', 
            'no_telp' => 'required', 
            'nik' => 'required',
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

        $user = auth()->user();
        $petugas_kesehatan = PetugasKesehatan::where('user_id',$user->id)->first();
        // $data['user_id'] = User::where("id", auth()->user()->id)->first()->id;
        $petugas_kesehatan->update([
            'nama' => request('nama'), 
            'jenis_kelamin' => request('jenis_kelamin'), 
            'tempat_lahir' => request('tempat_lahir'), 
            'tanggal_lahir' => request('tanggal_lahir'), 
            'no_telp' => request('no_telp'), 
            'nik' => request('nik'),
            'alamat' => request('alamat'),
            'dusun_id' => request('dusun_id'),
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data Petugas berhasil diubah",
            'data' => $petugas_kesehatan
        ], 200);

    }
}