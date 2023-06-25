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
use Kutia\Larafirebase\Facades\Larafirebase;

class JadwalPemeriksaanController extends Controller
{
    public function index() 
    {
        // $jadwal_pemeriksaan = JadwalPemeriksaan::with(['operator_posyandu', 'dusun'])->get();
        $login = auth()->user()->role_id;
        
        if($login == 1){
            $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
            ->select('*', 'tb_jadwal_pemeriksaan.id')
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
            ->select('*', 'tb_jadwal_pemeriksaan.id')
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

            $dusun = Dusun::where("id", $user->dusun_id)->first();
            $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
            ->select('*', 'tb_jadwal_pemeriksaan.id')
            ->join('tb_operator_posyandu', 'tb_operator_posyandu.id', '=', 'tb_jadwal_pemeriksaan.operator_posyandu_id')
            ->join('m_dusun', 'm_dusun.id', '=', 'tb_jadwal_pemeriksaan.dusun_id')
            ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
            ->where('m_desa.id', '=', $dusun->desa->id)
            ->get();

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $jadwal_pemeriksaan
            ]);
        }elseif($login == 4){
            $user = Keluarga::where("user_id", auth()->user()->id)->first();
        }
        $dusun = Dusun::where("id", $user->dusun_id)->first();
        $jadwal_pemeriksaan = DB::table('tb_jadwal_pemeriksaan')
        ->select('*', 'tb_jadwal_pemeriksaan.id')
        ->join('tb_operator_posyandu', 'tb_operator_posyandu.id', '=', 'tb_jadwal_pemeriksaan.operator_posyandu_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_jadwal_pemeriksaan.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where('m_desa.id', '=', $dusun->desa->id)
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

        $cek_jadwal = JadwalPemeriksaan::where('dusun_id', $request->dusun_id)
        ->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_berakhir])
        ->orWhereBetween('waktu_berakhir', [$request->waktu_mulai, $request->waktu_berakhir])
        ->get();

        if(!$cek_jadwal->isEmpty()){
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data jadwal sudah ada"
            ], 400);
        }

        $jadwal_pemeriksaan = JadwalPemeriksaan::create($request->all());

        //get user fcm token by desa for notificatation
        $user_petugas = DB::table('tb_petugas_kesehatan')
        ->select('*')
        ->join('users', 'users.id', '=', 'tb_petugas_kesehatan.user_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_petugas_kesehatan.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where('m_desa.id', '=', $jadwal_pemeriksaan->dusun->desa_id)
        ->pluck('fcm_token')
        ->all();
        $user_peserta = DB::table('tb_keluarga')
        ->select('*')
        ->join('users', 'users.id', '=', 'tb_keluarga.user_id')
        ->join('m_dusun', 'm_dusun.id', '=', 'tb_keluarga.dusun_id')
        ->join('m_desa', 'm_desa.id', '=', 'm_dusun.desa_id')
        ->where('m_desa.id', '=', $jadwal_pemeriksaan->dusun->desa_id)
        ->pluck('fcm_token')
        ->all();
        // return $user_peserta;
        // dd($petugas_kesehatan);

        // $user = User::all('fcm_token')->pluck('fcm_token')->all();
      
        Larafirebase::withTitle("Jadwal Posyandu")
            ->withBody($request->jenis_pemeriksaan)
            ->sendNotification($user_petugas);

        Larafirebase::withTitle("Jadwal Posyandu")
            ->withBody($request->jenis_pemeriksaan)
            ->sendNotification($user_peserta);
    

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data jadwal pemeriksaan berhasil ditambahkan",
            'data' => $jadwal_pemeriksaan
        ], 200);
    }

    public function show($id) 
    {
        $jadwal_pemeriksaan = JadwalPemeriksaan::findOrFail($id);

        $dusun = $jadwal_pemeriksaan->dusun;
        $desa = $dusun->desa;
        $kecamatan = $desa->kecamatan;
        $kabupaten = $kecamatan->kabupaten;
        $provinsi = $kabupaten->provinsi;

        if($jadwal_pemeriksaan){
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $jadwal_pemeriksaan
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'code' => 404,
                'data' => 'data tidak ditemukan'
            ]);
        }
        

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