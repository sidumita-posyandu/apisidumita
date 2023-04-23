<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DetailKeluarga;
use App\Keluarga;
use App\Balita;
use App\IbuHamil;
use Validator;
use Carbon\Carbon;

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
        $detail_keluargas = DetailKeluarga::with(['keluarga'])->where("status_dalam_keluarga","Ibu")->where("keluarga_id", $data)->get();

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
        
        $detail_keluarga = DetailKeluarga::create($request->all());

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
        $detail_keluarga->delete();

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

        if($umur == 0){
            $umur = $birthday->diffInMonths($now);
            
            if($umur == 0){
                $umur = $birthday->diffInWeeks($now);
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'data' => [
                        'umur' => $umur,
                        'format' => 'minggu',
                        'usia_bulan' => $umurbulan,
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => [
                    'umur' => $umur,
                    'format' => 'bulan',
                    'usia_bulan' => $umurbulan,
                ]
            ]);
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'umur' => $umur,
                'format' => 'tahun',
                'usia_bulan' => $umurbulan,
            ]
        ]);
    }
}