<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JadwalPemeriksaan;
use App\OperatorPosyandu;
use App\PetugasKesehatan;
use App\Kecamatan;
use App\Keluarga;
use App\Dusun;
use Validator;
use DB;

class JadwalPemeriksaanController extends Controller
{
    public function index() 
    {
        // $jadwal_pemeriksaan = JadwalPemeriksaan::with(['operator_posyandu', 'dusun'])->get();
        $login = auth()->user()->role_id;
        
        if($login == 1){
            $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
            ->select('*')
            ->join('tb_operator_posyandu', 'tb_operator_posyandu.id', '=', 'tb_jadwal_pemeriksaan.operator_posyandu_id')
            ->join('m_dusun', 'm_dusun.id', '=', 'tb_jadwal_pemeriksaan.dusun_id')
            ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
            ->join('m_kecamatan', 'm_kecamatan.id', '=', 'm_desa.kecamatan_id')
            ->get();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $jadwal_pemeriksaan
            ]);
        }elseif($login == 2){
            $user = OperatorPosyandu::where("user_id", auth()->user()->id)->first();

            $kecamatan = Kecamatan::where("id", $user->kecamatan_id)->first();
            $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
            ->select('*')
            ->join('tb_operator_posyandu', 'tb_operator_posyandu.id', '=', 'tb_jadwal_pemeriksaan.operator_posyandu_id')
            ->join('m_dusun', 'm_dusun.id', '=', 'tb_jadwal_pemeriksaan.dusun_id')
            ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
            ->join('m_kecamatan', 'm_kecamatan.id', '=', 'm_desa.kecamatan_id')
            ->where('m_kecamatan.id', '=', $kecamatan->id)
            ->get();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $jadwal_pemeriksaan
            ]);
        }elseif($login == 3){
            $user = PetugasKesehatan::where("user_id", auth()->user()->id)->first();
        }elseif($login == 4){
            $user = Keluarga::where("user_id", auth()->user()->id)->first();
        }
        $dusun = Dusun::where("id", $user->dusun_id)->first();
        $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
        ->select('*')
        ->join('tb_operator_posyandu', 'tb_operator_posyandu.id', '=', 'tb_jadwal_pemeriksaan.operator_posyandu_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_jadwal_pemeriksaan.dusun_id')
        ->where('m_dusun.id', '=', $dusun->id)
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $jadwal_pemeriksaan
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'jenis_pemeriksaan' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'operator_posyandu_id' => 'required|exists:tb_operator_posyandu,id',
            'dusun_id' => 'required|exists:m_dusun,id',
        ]);
            
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $jadwal_pemeriksaan = JadwalPemeriksaan::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data jadwal pemeriksaan berhasil ditambahkan",
            'data' => $jadwal_pemeriksaan
        ], 200);
    }

    public function show($id) 
    {
        $jadwal_pemeriksaan = JadwalPemeriksaan::findOrFail($id)->with(['operator_posyandu', 'dusun'])->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $jadwal_pemeriksaan
        ]);

    }

    public function update(Request $request, $id)
    {
        $validasi = Validator::make($request->all(), [
            'jenis_pemeriksaan' => 'required',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'operator_posyandu_id' => 'required|exists:tb_operator_posyandu,id',
            'dusun_id' => 'required|exists:m_dusun,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $jadwal_pemeriksaan = JadwalPemeriksaan::find($id);
        $jadwal_pemeriksaan->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data jadwal berhasil diubah",
            'data' => $jadwal_pemeriksaan
        ], 200);
    }

    public function destroy(JadwalPemeriksaan $jadwal_pemeriksaan)
    {
        $jadwal_pemeriksaan->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }
}