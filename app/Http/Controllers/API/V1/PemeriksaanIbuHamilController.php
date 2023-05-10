<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PemeriksaanIbuHamil;
use App\IbuHamil;
use App\PetugasKesehatan;
use Validator;
use DB;

class PemeriksaanIbuHamilController extends Controller
{
    public function index()
    {
        $pemeriksaan_ibu_hamils = DB::table('tb_detail_keluarga')
        ->select('*')
        ->join('tb_ibu_hamil', 'tb_ibu_hamil.detail_keluarga_id', '=', 'tb_detail_keluarga.id')
        ->join('tb_pemeriksaan_ibu_hamil', 'tb_pemeriksaan_ibu_hamil.ibu_hamil_id', '=', 'tb_ibu_hamil.id')
        ->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_perut' => 'required',
            'denyut_nadi' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'penanganan' => 'required',
            'keluhan' => 'required',
            'catatan' => 'required',
            'ibu_hamil_id' => 'required|exists:tb_ibu_hamil,id',
            'petugas_kesehatan_id' => 'required|exists:tb_petugas_kesehatan,id',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $pemeriksaan_ibu_hamil = PemeriksaanIbuHamil::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $pemeriksaan_ibu_hamil
        ], 200);
    }

    public function storePemeriksaanbyPegawai(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'umur_kandungan' => 'required',
            'lingkar_perut' => 'required',
            'denyut_nadi' => 'required',
            'denyut_jantung_bayi' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'penanganan' => 'required',
            'keluhan' => 'required',
            'catatan' => 'required',
            'ibu_hamil_id' => 'required|exists:tb_ibu_hamil,id',
        ]);

    
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $data = $request->all();
        $data['petugas_kesehatan_id'] = PetugasKesehatan::where("user_id", auth()->user()->id)->first()->id;
        $pemeriksaan_ibu_hamil = PemeriksaanIbuHamil::create($data);
        // dd($request);


        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data pemeriksaan ibu hamil berhasil ditambahkan",
            'data' => $pemeriksaan_ibu_hamil
        ], 200);
    }


    public function show($id) 
    {
        $pemeriksaan_ibu_hamil = PemeriksaanIbuHamil::with(['ibu_hamil', 'petugas_kesehatan'])->findOrFail($id);

        $detail_keluarga = $pemeriksaan_ibu_hamil->ibu_hamil()->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => array_merge(json_decode($pemeriksaan_ibu_hamil, true),json_decode($detail_keluarga['detail_keluarga'], true))
        ]);
    }

    public function getPemeriksaanByIbuHamil($id)
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::with('ibu_hamil')->where('ibu_hamil_id', $id)->orderBy('tanggal_pemeriksaan','desc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function getTwoLastPemeriksaanByIbuHamil($id)
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::where('ibu_hamil_id', $id)->orderBy('tanggal_pemeriksaan','desc')->limit(2)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);

    }

    public function getPemeriksaanByKandungan($id)
    {
        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::with('ibu_hamil')->where('ibu_hamil_id', $id)->orderBy('umur_kandungan','asc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);
    }

    public function indeksMasaTubuh($berat_badan, $tinggi_badan){
        $tinggi_badan_m = (float)$tinggi_badan/100;
        $tinggi_pangkat_2 = pow($tinggi_badan_m,2);
        $imt = $berat_badan/$tinggi_pangkat_2;
        return $imt;
    }

    public function hitungBeratRekomendasi($umur_kandungan, $status){
        $underweight = [[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1.44,2.59],[1.88,3.18],[2.32,3.77],[2.76,4.36],[3.2,4.95],[3.64,5.54],[4.08,6.13],[4.52,6.72],[4.96,7.31],[5.4,7.9],[5.84,8.49],[6.28,9.08],[6.72,9.67],[7.16,10.26],[7.6,10.85],[8.04,11.44],[8.48,12.03],[8.92,12.62],[9.36,13.21],[9.8,13.8],[10.24,14.39],[10.68,14.98],[11.12,15.57],[11.56,16.16],[12,16.75],[12.44,17.34],[12.88,17.93]];
        $normal = [[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1.39,2.52],[1.78,3.04],[2.17,3.56],[2.56,4.08],[2.95,4.6],[3.34,5.12],[3.73,5.64],[4.12,6.16],[4.51,6.68],[4.9,7.2],[5.29,7.72],[5.68,8.24],[6.07,8.76],[6.46,9.28],[6.85,9.8],[7.24,10.32],[7.63,10.84],[8.02,11.36],[8.41,11.88],[8.8,12.4],[9.19,12.92],[9.58,13.44],[9.97,13.96],[10.36,14.48],[10.75,15],[11.14,15.52],[11.53,16.04]];
        $overweight = [[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1.23,2.35],[1.46,2.7],[1.69,3.05],[1.92,3.4],[2.15,3.75],[2.38,4.1],[2.61,4.45],[2.84,4.8],[3.07,5.15],[3.3,5.5],[3.53,5.85],[3.76,6.2],[3.99,6.55],[4.22,6.9],[4.45,7.25],[4.68,7.6],[4.91,7.95],[5.14,8.3],[5.37,8.65],[5.6,9],[5.83,9.35],[6.06,9.7],[6.29,10.05],[6.52,10.4],[6.75,10.75],[6.98,11.1],[7.21,11.45]];
        $obese = [[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1,2],[1.17,2.27],[1.34,2.54],[1.51,2.81],[1.68,3.08],[1.85,3.35],[2.02,3.62],[2.19,3.89],[2.36,4.16],[2.53,4.43],[2.7,4.7],[2.87,4.97],[3.04,5.24],[3.21,5.51],[3.38,5.78],[3.55,6.05],[3.72,6.32],[3.89,6.59],[4.06,6.86],[4.23,7.13],[4.4,7.4],[4.57,7.67],[4.74,7.94],[4.91,8.21],[5.08,8.48],[5.25,8.75],[5.42,9.02],[5.59,9.29]];

        if($status == "underweight"){
            $bb_rekomendasi = $underweight[$umur_kandungan];
        }elseif($status == "normal"){
            $bb_rekomendasi = $normal[$umur_kandungan];
        }elseif($status == "overweight"){
            $bb_rekomendasi = $overweight[$umur_kandungan];
        }elseif($status == "obese"){
            $bb_rekomendasi = $obese[$umur_kandungan];
        }else{
            return "tidak dapat menemukan kualifikasi yang sesuai";
        }

        return $bb_rekomendasi;
    }

    public function cekBeratIbuHamil(Request $request){

        $ibu_hamil_id = IbuHamil::where('id', $request->ibu_hamil_id)->first();

        $status = "";
        $bb_prakehamilan = (float)$ibu_hamil_id->berat_badan_prakehamilan;
        $tb_prakehamilan = (float)$ibu_hamil_id->tinggi_badan_prakehamilan;
        $umur_kandungan = (int)$request->umur_kandungan;
        $berat_badan = (float)$request->berat_badan;
        

        $imt = $this->indeksMasaTubuh($bb_prakehamilan, $tb_prakehamilan);
        
        if($imt<18.5){
            $status = "underweight";
        }
        elseif ($imt> 10.5 && $imt<24.9) {
            $status = "normal";
        }
        elseif ($imt> 25 && $imt<29.9) {
            $status = "overweight";
        }
        elseif ($imt>=30) {
            $status = "obese";
        }else{
            $status = "tidak dapat memuat data";
        }
        
        $rekomendasi = $this->hitungBeratRekomendasi($umur_kandungan, $status);

        $berat_minimal = $berat_badan + round($rekomendasi[0]);
        $berat_maksimal = $berat_badan + round($rekomendasi[1]);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'imt' => (float) number_format($imt,2),
                'status' => $status,
                'bb_minimal' => $berat_minimal,
                'bb_maksimal' => $berat_maksimal,
            ]
        ], 200);
    }

    public function getIbuHamilByUsiaKandungan(Request $request, $id){
        // $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::with('ibu_hamil')->where('ibu_hamil_id', $id)->orderBy('umur_kandungan','asc')->get();
        // $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::with('ibu_hamil')->where('ibu_hamil_id', $id)
        // ->selectRaw('')
        // ->orderBy('umur_kandungan','asc')->get();

        $pemeriksaan_ibu_hamils = PemeriksaanIbuHamil::
            where('ibu_hamil_id', $id)
            ->join('tb_ibu_hamil', 'tb_ibu_hamil.id', '=', 'tb_pemeriksaan_ibu_hamil.ibu_hamil_id')
            ->selectRaw('berat_badan - tb_ibu_hamil.berat_badan_prakehamilan AS berat_badan, umur_kandungan') 
            ->orderBy('umur_kandungan','asc')
            ->get();

        // dd($pemeriksaan_ibu_hamils);
        // foreach ($pemeriksaan_ibu_hamils as $key => $value) {
        //     $berat_badan[] = $value->berat_badan - $value->ibu_hamil->berat_badan_prakehamilan;
        //     $umur_kandungan[] = $value->umur_kandungan;
        // }


        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_ibu_hamils
        ]);
    }
}