<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PemeriksaanBalita;
use App\DetailKeluarga;
use App\PetugasKesehatan;
use App\DetailPemeriksaanBalita;
use Validator;
use DB;

class PemeriksaanBalitaController extends Controller
{
    public function index() 
    {
        $pemeriksaan_balitas = DB::table('tb_detail_keluarga')
        ->select('*')
        ->join('tb_balita', 'tb_balita.detail_keluarga_id', '=', 'tb_detail_keluarga.id')
        ->join('tb_pemeriksaan_balita', 'tb_pemeriksaan_balita.balita_id', '=', 'tb_balita.id')
        ->get();
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_kepala' => 'required',
            'lingkar_lengan' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'balita_id' => 'required|exists:tb_balita,id',
            'petugas_kesehatan_id' => 'required|exists:tb_petugas_kesehatan,id',
            'penanganan' => 'required',
            'catatan' => 'required',
            'keluhan' => 'required',
            'dokter_id' => 'required',
            'vitamin_id' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }

        $pemeriksaan_balita = PemeriksaanBalita::create($request->all());

        if($request->has('vaksin_id')){
            foreach ($request->vaksin_id as $key => $value) {
                $detail_pemeriksaan_balita = DetailPemeriksaanBalita::create([
                    'pemeriksaan_balita_id' => $pemeriksaan_balita->id,
                    'balita_id' => $pemeriksaan_balita->balita_id,
                    'vaksin_id' => $value
                ]);
            }
        }
        
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $pemeriksaan_balita
        ], 200);
    }

    public function storePemeriksaanbyPegawai(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'umur_balita' => 'required',
            'lingkar_kepala' => 'required',
            'lingkar_lengan' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'balita_id' => 'required|exists:tb_balita,id',
            'penanganan' => 'required',
            'catatan' => 'required',
            'keluhan' => 'required',
            'dokter_id' => 'required',
            'vitamin_id' => 'required',
            // 'vaksin_id' => 'required',
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
        $pemeriksaan_balita = PemeriksaanBalita::create($data);
        // dd($data);

        if($request->has('vaksin_id')){
            foreach ($request->vaksin_id as $key => $value) {
                $detail_pemeriksaan_balita = DetailPemeriksaanBalita::create([
                    'pemeriksaan_balita_id' => $pemeriksaan_balita->id,
                    'balita_id' => $pemeriksaan_balita->balita_id,
                    'vaksin_id' => $value
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil ditambahkan",
            'data' => $pemeriksaan_balita
        ], 200);
    }


    public function show($id) 
    {
        $pemeriksaan_balitas = PemeriksaanBalita::with(['balita', 'petugas_kesehatan', 'dokter', 'detail_pemeriksaan_balita','vaksin'])->findOrFail($id);

        $detail_keluarga = $pemeriksaan_balitas->balita()->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => array_merge(json_decode($pemeriksaan_balitas, true),json_decode($detail_keluarga['detail_keluarga'], true))
        ]);

    }

   
    public function getPemeriksaanByBalita($id)
    {
        $pemeriksaan_balitas = PemeriksaanBalita::with('balita')->where('balita_id', $id)->orderBy('tanggal_pemeriksaan','desc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function getPemeriksaanByUmur($id)
    {
        $pemeriksaan_balitas = PemeriksaanBalita::with('balita')->where('balita_id', $id)->orderBy('umur_balita','asc')->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function getDetailPemeriksaanByBalita($id){
        $pemeriksaan_balitas = PemeriksaanBalita::where('balita_id', $id)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);
    }

    public function getTwoLastPemeriksaanByBalita($id)
    {
        $pemeriksaan_balitas = PemeriksaanBalita::where('balita_id', $id)->orderBy('tanggal_pemeriksaan','desc')->limit(2)->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $pemeriksaan_balitas
        ]);

    }

    public function update(Request $request, PemeriksaanBalita $pemeriksaan_balita)
    {
        $validasi = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required',
            'lingkar_kepala' => 'required',
            'lingkar_lengan' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'balita_id' => 'required',
            'petugas_kesehatan_id' => 'required',
            'penanganan' => 'required',
            'catatan' => 'required',
            'keluhan' => 'required',
            'dokter_id' => 'required',
            'vitamin_id' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $pemeriksaan_balita->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil diubah",
            'data' => $pemeriksaan_balita
        ], 200);
    }

    public function destroy(PemeriksaanBalita $pemeriksaan_balita)
    {
        $pemeriksaan_balita->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data bulan imunisasi berhasil dihapus!",
        ], 200);
    }

    public function checkWeightBoys(Request $request)
    {
        $data0 = [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5,
        10.7, 10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2];
        $datamin1 = [2.9, 3.9, 4.9, 5.7, 6.2, 6.7, 7.1, 7.4, 7.7, 8.0, 8.2, 8.4, 8.6, 8.8, 9.0, 9.2, 9.4, 9.6,
        9.8, 10.0, 10.1, 10.3, 10.5, 10.7, 10.8];
        $datamin2 = [2.5, 3.4, 4.3, 5.0, 5.6, 6.0, 6.4, 6.7, 6.9, 7.1, 7.4, 7.6, 7.7, 7.9, 8.1, 8.3, 8.4, 8.6,
        8.8, 8.9, 9.1, 9.2, 9.4, 9.5, 9.7];
        $datamin3 = [2.1, 2.9, 3.8, 4.4, 4.9, 5.3, 5.7, 5.9, 6.2, 6.4, 6.6, 6.8, 6.9, 7.1, 7.2, 7.4, 7.5, 7.7,
        7.8, 8.0, 8.1, 8.2, 8.4, 8.5, 8.6];
        $data1 = [3.9, 5.1, 6.3, 7.2, 7.8, 8.4, 8.8, 9.2, 9.6, 9.9, 10.2, 10.5, 10.8, 11.0, 11.3, 11.5,
        11.7, 12.0, 12.2, 12.5, 12.7, 12.9, 13.2, 13.4, 13.6];
        $data2 = [4.4, 5.8, 7.1, 8.0, 8.7, 9.3, 9.8, 10.3, 10.7, 11.0, 11.4, 11.7, 12.0, 12.3, 12.6, 12.8,
        13.1, 13.4, 13.7, 13.9, 14.2, 14.5, 14.7, 15.0, 15.3];
        $data3 = [5.0, 6.6, 8.0, 9.0, 9.7, 10.4, 10.9, 11.4, 11.9, 12.3, 12.7, 13.0, 13.3, 13.7, 14.0,
        14.3, 14.6, 14.9, 15.3, 15.6, 15.9, 16.2, 16.5, 16.8, 17.1];

        $data_ukur = $request->all();
        $weight = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($weight < $datamin2[$age] && $weight >= $datamin3[$age]){
            $status = "Kurus";
        }
        elseif($weight > $data2[$age] && $weight <= $data3[$age]){
            $status = "Gemuk";
        }
        elseif($weight < $datamin3[$age]){
            $status = "Sangat Kurus";
        }
        elseif($weight > $data3[$age]){
            $status = "Sangat Gemuk";
        }
        elseif($weight >= $datamin2[$age] && $weight <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $weight
            ],
        ], 200);
    }
    

    public function checkWeightGirls(Request $request){
        $data0 = [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8,
        10.0, 10.2, 10.4, 10.6, 10.9, 11.1, 11.3, 11.5,];
        $data1 = [3.7, 4.8, 5.8, 6.6, 7.3, 7.8, 8.2, 8.6, 9.0, 9.3, 9.6, 9.9, 10.1, 10.4, 10.6, 10.9, 11.1,
        11.4, 11.6, 11.8, 12.1, 12.3, 12.5, 12.8, 13.0,];
        $data2 = [4.2, 5.5, 6.6, 7.5, 8.2, 8.8, 9.3, 9.8, 10.2, 10.5, 10.9, 11.2, 11.5, 11.8, 12.1, 12.4,
        12.6, 12.9, 13.2, 13.5, 13.7, 14.0, 14.3, 14.6, 14.8,];
        $data3 = [4.8, 6.2, 7.5, 8.5, 9.3, 10.0, 10.6, 11.1, 11.6, 12.0, 12.4, 12.8, 13.1, 13.5, 13.8,
        14.1, 14.5, 14.8, 15.1, 15.4, 15.7, 16.0, 16.4, 16.7, 17.0,];
        $datamin1 = [2.8, 3.6, 4.5, 5.2, 5.7, 6.1, 6.5, 6.8, 7.0, 7.3, 7.5, 7.7, 7.9, 8.1, 8.3, 8.5, 8.7, 8.9,
        9.1, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2,];
        $datamin2 = [2.4, 3.2, 3.9, 4.5, 5.0, 5.4, 5.7, 6.0, 6.3, 6.5, 6.7, 6.9, 7.0, 7.2, 7.4, 7.6, 7.7, 7.9,
        8.1, 8.2, 8.4, 8.6, 8.7, 8.9, 9.0,];
        $datamin3 = [2.0, 2.7, 3.4, 4.0, 4.4, 4.8, 5.1, 5.3, 5.6, 5.8, 5.9, 6.1, 6.3, 6.4, 6.6, 6.7, 6.9, 7.0,
        7.2, 7.3, 7.5, 7.6, 7.8, 7.9, 8.1,];

        $data_ukur = $request->all();
        $weight = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($weight < $datamin2[$age] && $weight >= $datamin3[$age]){
            $status = "Kurus";
        }
        elseif($weight > $data2[$age] && $weight <= $data3[$age]){
            $status = "Gemuk";
        }
        elseif($weight < $datamin3[$age]){
            $status = "Sangat Kurus";
        }
        elseif($weight > $data3[$age]){
            $status = "Sangat Gemuk";
        }
        elseif($weight >= $datamin2[$age] && $weight <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $weight,
            ],
        ], 200);
    }

    public function checkHeightBoys(Request $request){
        $data0 = [49.9, 54.7, 58.4, 61.4, 63.9, 65.9, 67.6, 69.2, 70.6, 72, 73.3, 74.5, 75.7, 76.9, 78.0,
        79.1, 80.2, 81.2, 82.3, 83.2, 84.2, 85.1, 86, 86.9, 87.8];
        $data1 = [51.8, 56.7, 60.4, 63.5, 66, 68, 69.8, 71.3, 72.8, 74.2, 75.6, 76.9, 78.1, 79.3, 80.5,
        81.7, 82.8, 83.9, 85, 86, 87, 88, 89, 89.9, 90.9];
        $data2 = [53.7, 58.6, 62.4, 65.5, 68, 70.1, 71.9, 73.5, 75, 76.5, 77.9, 79.2, 80.5, 81.8, 83, 84.2,
        85.4, 86.5, 87.7, 88.8, 89.8, 90.9, 91.9, 92.9, 93.9];
        $data3 = [55.6, 60.6, 64.4, 67.6, 70.1, 72.2, 74, 75.7, 77.2, 78.7, 80.1, 81.5, 82.9, 84.2, 85.5,
        86.7, 88, 89.2, 90.4, 91.5, 92.6, 93.8, 94.9, 95.9, 97];
        $datamin1 = [48, 52.8, 56.4, 59.4, 61.8, 63.8, 65.5, 67, 68.4, 69.7, 71, 72.2, 73.4, 74.5, 75.6, 76.7,
        77.6, 78.6, 79.6, 80.5, 81.4, 82.3, 83.1, 83.9, 84.8];
        $datamin2 = [46.1, 50.8, 54.4, 57.3, 59.7, 61.7, 63.3, 64.8, 66.2, 67.5, 68.7, 69.9, 71, 72.1, 73.1,
        74.1, 75, 76, 76.9, 77.7, 78.6, 79.4, 80.2, 81, 81.7];
        $datamin3 = [44.2, 48.9, 52.4, 55.3, 57.6, 59.6, 61.2, 62.7, 64, 65.2, 66.4, 67.6, 68.6, 69.6, 70.6, 
        71.6, 72.5, 73.3, 74.2, 75, 75.8, 76.5, 77.2, 78, 78.7, ];

        $data_ukur = $request->all();
        $weight = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($weight < $datamin2[$age] && $weight >= $datamin3[$age]){
            $status = "Pendek";
        }
        elseif($weight > $data2[$age] && $weight <= $data3[$age]){
            $status = "Tinggi";
        }
        elseif($weight < $datamin3[$age]){
            $status = "Sangat Pendek";
        }
        elseif($weight > $data3[$age]){
            $status = "Sangat Tinggi";
        }
        elseif($weight >= $datamin2[$age] && $weight <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $weight,
            ],
        ], 200);
    }

    public function checkHeightGirls(Request $request){
        $data0 = [49.1, 53.7, 57.1, 59.8, 62.1, 64, 65.7, 67.3, 68.7, 70.1, 71.5, 72.8, 74, 75.2, 76.4,
        77.5, 78.6, 79.7, 80.7, 81.7, 82.7, 83.7, 84.6, 85.5, 86.4];
        $data1 = [51, 55.6, 59.1, 61.9, 64.3, 66.2, 68, 69.6, 71.1, 72.6, 73.9, 75.3, 76.6, 77.8, 79.1,
        80.2, 81.4, 82.5, 83.6, 84.7, 85.7, 86.7, 87.7, 88.7, 89.6];
        $data2 = [52.9, 57.6, 61.1, 64, 66.4, 68.5, 70.3, 71.9, 73.5, 75, 76.4, 77.8, 79.2, 80.5, 81.7, 83,
        84.2, 85.4, 86.5, 87.6, 88.7, 89.8, 90.8, 91.9, 92.9];
        $data3 = [54.7, 59.5, 63.2, 66.1, 68.6, 70.7, 72.5, 74.2, 75.8, 77.4, 78.9, 80.3, 81.7, 83.1, 84.4,
        85.7, 87, 88.2, 89.4, 90.6, 91.7, 92.9, 94, 95, 96.1];
        $datamin1 = [47.3, 51.7, 55, 57.7, 59.9, 61.8, 63.5, 65, 66.4, 67.7, 69, 70.3, 71.4, 72.6, 73.7, 74.8,
        75.8, 76.8, 77.8, 78.8, 79.7, 80.6, 81.5, 82.3, 83.2];
        $datamin2 = [45.4, 49.8, 53, 55.6, 57.8, 59.6, 61.2, 62.7, 64, 65.3, 66.5, 67.7, 68.9, 70, 71, 72, 73,
        74, 74.9, 75.8, 76.7, 77.5, 78.4, 79.2, 80];
        $datamin3 = [43.6, 47.8, 51, 53.5, 55.6, 57.4, 58.9, 60.3, 61.7, 62.9, 64.1, 65.2, 66.3, 67.3, 68.3,
        69.3, 70.2, 71.1, 72, 72.8, 73.7, 74.5, 75.2, 76, 76.7];

        $data_ukur = $request->all();
        $height = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($height < $datamin2[$age] && $height >= $datamin3[$age]){
            $status = "Pendek";
        }
        elseif($height > $data2[$age] && $height <= $data3[$age]){
            $status = "Tinggi";
        }
        elseif($height < $datamin3[$age]){
            $status = "Sangat Pendek";
        }
        elseif($height > $data3[$age]){
            $status = "Sangat Tinggi";
        }
        elseif($height >= $datamin2[$age] && $height <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $height,
            ],
        ], 200);
    }

    public function checkHeadBoys(Request $request){
        $data0 = [34.5, 37.3, 39.1, 40.5, 41.6, 42.6, 43.3, 44.0, 44.5, 45.0, 45.4, 45.8, 46.1, 46.3, 46.6, 46.8,
        47.0, 47.2, 47.4, 47.5, 47.7, 47.8, 48.0, 48.1, 48.3];
        $data1 = [35.7, 38.4, 40.3, 41.7, 42.8, 43.8, 44.6, 45.2, 45.8, 46.3, 46.7, 47.0, 47.4, 47.6, 47.9, 48.1, 
        48.3, 48.5, 48.7, 48.9, 49.0, 49.2, 49.3, 49.5, 49.6, ];
        $data2 = [37.0, 39.6, 41.5, 42.9, 44.0, 45.0, 45.8, 46.4, 47.0, 47.5, 47.9, 48.3, 48.6, 48.9, 49.2, 49.4, 
        49.6, 49.8, 50.0, 50.2, 50.4, 50.5, 50.7, 50.8, 51.0, ];
        $data3 = [38.3, 40.8, 42.6, 44.1, 45.2, 46.2, 47.0, 47.7, 48.3, 48.8, 49.2, 49.6, 49.9, 50.2, 50.5, 50.7, 
        51.0, 51.2, 51.4, 51.5, 51.7, 51.9, 52.0, 52.2, 52.3, ];
        $datamin1 = [33.2, 36.1, 38.0, 39.3, 40.4, 41.4, 42.1, 42.7, 43.3, 43.7, 44.1, 44.5, 44.8, 45.0, 45.3, 45.5, 
        45.7, 45.9, 46.0, 46.2, 46.4, 46.5, 46.6, 46.8, 46.9, ];
        $datamin2 = [31.9, 34.9, 36.8, 38.1, 39.2, 40.1, 40.9, 41.5, 42.0, 42.5, 42.9, 43.2, 43.5, 43.8, 44.0, 44.2, 
        44.4, 44.6, 44.7, 44.9, 45.0, 45.2, 45.3, 45.4, 45.5, ];
        $datamin3 = [30.7, 33.8, 35.6, 37.0, 38.0, 38.9, 39.7, 40.3, 40.8, 41.2, 41.6, 41.9, 42.2, 42.5, 42.7, 42.9, 
        43.1, 43.2, 43.4, 43.5, 43.7, 43.8, 43.9, 44.1, 44.2, ];

        $data_ukur = $request->all();
        $size = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($size < $datamin2[$age] && $size >= $datamin3[$age]){
            $status = "Kecil";
        }
        elseif($size > $data2[$age] && $size <= $data3[$age]){
            $status = "Besar";
        }
        elseif($size < $datamin3[$age]){
            $status = "Sangat Kecil";
        }
        elseif($size > $data3[$age]){
            $status = "Sangat Besar";
        }
        elseif($size >= $datamin2[$age] && $size <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $size,
            ],
        ], 200);
    }

    public function checkHeadGirls(Request $request){
        $data0 = [33.9, 36.5, 38.3, 39.5, 40.6, 41.5, 42.2, 42.8, 43.4, 43.8, 44.2, 44.6, 44.9, 45.2, 45.4, 45.7, 45.9, 
        46.1, 46.2, 46.4, 46.6, 46.7, 46.9, 47.0, 47.2, ];
        $data1 = [35.1, 37.7, 39.5, 40.8, 41.8, 42.7, 43.5, 44.1, 44.7, 45.2, 45.6, 45.9, 46.3, 46.5, 46.8, 47.0, 47.2, 
        47.4, 47.6, 47.8, 48.0, 48.1, 48.3, 48.4, 48.6, ];
        $data2 = [36.2, 38.9, 40.7, 42.0, 43.1, 44.0, 44.8, 45.5, 46.0, 46.5, 46.9, 47.3, 47.6, 47.9, 48.2, 48.4, 48.6, 
        48.8, 49.0, 49.2, 49.4, 49.5, 49.7, 49.8, 50.0, ];
        $data3 = [37.4, 40.1, 41.9, 43.3, 44.4, 45.3, 46.1, 46.8, 47.4, 47.8, 48.3, 48.6, 49.0, 49.3, 49.5, 49.8, 50.0, 
        50.2, 50.4, 50.6, 50.7, 50.9, 51.1, 51.2, 51.4, ];
        $datamin1 = [32.7, 35.4, 37.0, 38.3, 39.3, 40.2, 40.9, 41.5, 42.0, 42.5, 42.9, 43.2, 43.5, 43.8, 44.1, 44.3, 44.5, 
        44.7, 44.9, 45.0, 45.2, 45.3, 45.5, 45.6, 45.8, ];
        $datamin2 = [31.5, 34.2, 35.8, 37.1, 38.1, 38.9, 39.6, 40.2, 40.7, 41.2, 41.5, 41.9, 42.2, 42.4, 42.7, 42.9, 43.1, 
        43.3, 43.5, 43.6, 43.8, 44.0, 44.1, 44.3, 44.4, ];
        $datamin3 = [30.3, 33.0, 34.6, 35.8, 36.8, 37.6, 38.3, 38.9, 39.4, 39.8, 40.2, 40.5, 40.8, 41.1, 41.3, 41.5, 41.7, 
        41.9, 42.1, 42.3, 42.4, 42.6, 42.7, 42.9, 43.0, ];

        $data_ukur = $request->all();
        $size = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($size < $datamin2[$age] && $size >= $datamin3[$age]){
            $status = "Kecil";
        }
        elseif($size > $data2[$age] && $size <= $data3[$age]){
            $status = "Besar";
        }
        elseif($size < $datamin3[$age]){
            $status = "Sangat Kecil";
        }
        elseif($size > $data3[$age]){
            $status = "Sangat Besar";
        }
        elseif($size >= $datamin2[$age] && $size <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $size,
            ],
        ], 200);
    }

    public function checkArmBoys(Request $request){
        $data0 = [13.5, 13.8, 14.1, 14.2, 14.4, 14.5, 14.5, 14.6, 14.6, 14.6, 14.7, 14.7, 14.7, 14.8, 14.8, 14.8, 14.9, 14.9, 
        15.0, 15.0, 15.1, 15.2, ];
        $data1 = [14.5, 14.9, 15.2, 15.4, 15.5, 15.6, 15.7, 15.7, 15.8, 15.8, 15.8, 15.9, 15.9, 16.0, 16.0, 16.0, 16.1, 16.1, 
        16.2, 16.3, 16.3, 16.4, ];
        $data2 = [15.6, 16.0, 16.3, 16.5, 16.7, 16.8, 16.9, 17.0, 17.0, 17.1, 17.1, 17.1, 17.2, 17.2, 17.3, 17.3, 17.4, 17.4, 
        17.5, 17.6, 17.6, 17.7, ];
        $data3 = [16.7, 17.2, 17.5, 17.8, 18.0, 18.1, 18.2, 18.3, 18.3, 18.4, 18.4, 18.5, 18.5, 18.6, 18.6, 18.7, 18.8, 18.8, 
        18.9, 19.0, 19.1, 19.2, ];
        $datamin1 = [12.5, 12.8, 13.0, 13.2, 13.3, 13.4, 13.4, 13.5, 13.5, 13.6, 13.6, 13.6, 13.6, 13.7, 13.7, 13.7, 13.8, 13.8, 
        13.9, 13.9, 14.0, 14.0, ];
        $datamin2 = [11.6, 11.8, 12.0, 12.2, 12.3, 12.4, 12.4, 12.5, 12.5, 12.5, 12.6, 12.6, 12.6, 12.7, 12.7, 12.7, 12.8, 12.8, 
        12.8, 12.9, 12.9, 13.0, ];
        $datamin3 = [10.7, 10.9, 11.1, 11.3, 11.4, 11.4, 11.5, 11.5, 11.6, 11.6, 11.6, 11.6, 11.7, 11.7, 11.7, 11.8, 11.8, 11.9, 
        11.9, 11.9, 12.0, 12.0, ];

        $data_ukur = $request->all();
        $size = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($size < $datamin2[$age] && $size >= $datamin3[$age]){
            $status = "Kecil";
        }
        elseif($size > $data2[$age] && $size <= $data3[$age]){
            $status = "Besar";
        }
        elseif($size < $datamin3[$age]){
            $status = "Sangat Kecil";
        }
        elseif($size > $data3[$age]){
            $status = "Sangat Besar";
        }
        elseif($size >= $datamin2[$age] && $size <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $size,
            ],
        ], 200);
    }

    public function checkArmGirls(Request $request){
        $data0 = [13.0, 13.4, 13.6, 13.8, 13.9, 14.0, 14.1, 14.1, 14.2, 14.2, 14.2, 14.3, 14.3, 14.4, 14.4, 14.5, 14.5, 14.6, 14.7, 
        14.7, 14.8, 14.9, ];
        $data1 = [14.2, 14.5, 14.8, 15.0, 15.1, 15.2, 15.3, 15.4, 15.4, 15.4, 15.5, 15.5, 15.6, 15.6, 15.7, 15.7, 15.8, 15.8, 15.9, 
        16.0, 16.1, 16.1, ];
        $data2 = [15.4, 15.8, 16.1, 16.3, 16.5, 16.6, 16.7, 16.7, 16.8, 16.8, 16.8, 16.9, 16.9, 17.0, 17.0, 17.1, 17.1, 17.2, 17.3, 
        17.4, 17.5, 17.5, ];
        $data3 = [16.8, 17.2, 17.6, 17.8, 18.0, 18.1, 18.2, 18.2, 18.3, 18.3, 18.3, 18.4, 18.4, 18.5, 18.5, 18.6, 18.7, 18.7, 18.8, 
        18.9, 19.0, 19.1, ];
        $datamin1 = [12.0, 12.3, 12.5, 12.7, 12.8, 12.9, 12.9, 13.0, 13.0, 13.1, 13.1, 13.2, 13.2, 13.3, 13.3, 13.4, 13.4, 13.5, 13.5, 
        13.6, 13.7, 13.7, ];
        $datamin2 = [11.1, 11.3, 11.5, 11.7, 11.8, 11.9, 11.9, 12.0, 12.0, 12.1, 12.1, 12.1, 12.2, 12.2, 12.3, 12.3, 12.4, 12.4, 12.5, 
        12.6, 12.6, 12.7, ];
        $datamin3 = [10.2, 10.5, 10.7, 10.8, 10.9, 11.0, 11.0, 11.1, 11.1, 11.1, 11.2, 11.2, 11.3, 11.3, 11.4, 11.4, 11.4, 11.5, 11.6, 
        11.6, 11.7, 11.7, ];

        $data_ukur = $request->all();
        $size = (float) $data_ukur['data_ukur'];
        $age = (int) $data_ukur['umur'];

        if($size < $datamin2[$age] && $size >= $datamin3[$age]){
            $status = "Kecil";
        }
        elseif($size > $data2[$age] && $size <= $data3[$age]){
            $status = "Besar";
        }
        elseif($size < $datamin3[$age]){
            $status = "Sangat Kecil";
        }
        elseif($size > $data3[$age]){
            $status = "Sangat Besar";
        }
        elseif($size >= $datamin2[$age] && $size <= $data2[$age]){
            $status = "Normal";
        }else{
            return "nothing";
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'status' => $status,
                'umur' => $age,
                'data_ukur' => $size,
            ],
        ], 200);
    }
}