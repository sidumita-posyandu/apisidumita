<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('register-admin', 'AuthController@registerAdmin');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('change-password', 'AuthController@changePassword');
    Route::get('me', 'AuthController@me');
    Route::post('sendPasswordResetLink', 'PasswordResetRequestController@sendEmail');
    Route::post('resetPassword', 'ChangePasswordController@passwordResetProcess');
});


Route::apiResource('provinsi', API\V1\MasterData\ProvinsiController::class);
Route::apiResource('kabupaten', API\V1\MasterData\KabupatenController::class);
Route::apiResource('kecamatan', API\V1\MasterData\KecamatanController::class);
Route::apiResource('desa', API\V1\MasterData\DesaController::class);
Route::apiResource('dusun', API\V1\MasterData\DusunController::class);

Route::post('fetch-provinsi', 'API\V1\MasterData\KabupatenController@fetchProvinsi');
Route::post('fetch-kabupaten', 'API\V1\MasterData\KecamatanController@fetchKabupaten');
Route::post('fetch-kecamatan', 'API\V1\MasterData\DesaController@fetchKecamatan');
Route::post('fetch-desa', 'API\V1\MasterData\DusunController@fetchDesa');

Route::apiResource('konten', API\V1\KontenController::class);
Route::post('update/konten/{id}', 'API\V1\KontenController@updateKonten');

//must login
Route::middleware(['jwt.verify'])->group(function () {
    Route::apiResource('user', API\V1\UserController::class);
    Route::apiResource('role', API\V1\RoleController::class);


    Route::apiResource('keluarga', API\V1\KeluargaController::class);
    Route::apiResource('detail-keluarga', API\V1\DetailKeluargaController::class);
    Route::get('umur/{id}', 'API\V1\DetailKeluargaController@getUmur');
    Route::get('me/keluarga', 'API\V1\KeluargaController@showMyKeluarga');
    Route::get('me/detail-keluarga', 'API\V1\DetailKeluargaController@showMyDetailKeluarga');
    Route::get('me/balitas-from-keluarga', 'API\V1\DetailKeluargaController@showMyBalitas');
    Route::get('me/ibuhamils-from-keluarga', 'API\V1\DetailKeluargaController@showMyIbuHamils');
    Route::post('me/create-detail-keluarga', 'API\V1\DetailKeluargaController@storeMyDetKeluarga');
    Route::post('me/update-detail-keluarga', 'API\V1\DetailKeluargaController@updateMyDetKeluarga');
    Route::post('me/create-keluarga', 'API\V1\KeluargaController@storeMyKeluarga');
    Route::post('me/update-keluarga', 'API\V1\KeluargaController@updateMyKeluarga');
    
    Route::get('petugas/with-keluarga', 'API\V1\KeluargaController@showKeluargaForPetugas');

    Route::apiResource('balita', API\V1\BalitaController::class);
    Route::get('me/balita', 'API\V1\BalitaController@showMyBalitas');
    Route::get('petugas/with-balita', 'API\V1\BalitaController@showBalitaForPetugas');
    
    Route::apiResource('ibu-hamil', API\V1\IbuHamilController::class);
    Route::get('me/ibu-hamil', 'API\V1\IbuHamilController@showMyIbuHamils');
    Route::get('petugas/with-ibu-hamil', 'API\V1\IbuHamilController@showIbuHamilForPetugas');
    Route::apiResource('vitamin', API\V1\VitaminController::class);
    Route::apiResource('vaksin', API\V1\VaksinController::class);

    Route::apiResource('pemeriksaan-balita', API\V1\PemeriksaanBalitaController::class);
    Route::apiResource('detailpemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);
    
    Route::post('pemeriksaan-balita-admin', 'API\V1\PemeriksaanBalitaController@storeAdmin');


    Route::get('pemeriksaan-balita/balita/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByBalita');
    Route::get('detailpemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanByBalita');
    Route::get('detailpemeriksaan/balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanBalita');
    Route::get('pemeriksaan-balita/latest-balita/{id}','API\V1\PemeriksaanBalitaController@getTwoLastPemeriksaanByBalita');
    Route::get('pemeriksaan-balita/umur/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByUmur');
    Route::post('pemeriksaan-balita/byPetugas', 'API\V1\PemeriksaanBalitaController@storePemeriksaanbyPegawai');
    Route::post('destroy/pemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@destroyPemeriksaanBalita');
   

    Route::apiResource('pemeriksaan-ibuhamil', API\V1\PemeriksaanIbuHamilController::class);

    Route::get('pemeriksaan-ibuhamil/ibuhamil/{id}', 'API\V1\PemeriksaanIbuHamilController@getPemeriksaanByIbuHamil');
    Route::get('pemeriksaan-ibuhamil/latest-ibuhamil/{id}','API\V1\PemeriksaanIbuHamilController@getTwoLastPemeriksaanByIbuHamil');
    Route::get('pemeriksaan-ibuhamil/kandungan/{id}', 'API\V1\PemeriksaanIbuHamilController@getPemeriksaanByKandungan');
    Route::post('pemeriksaan-ibu-hamil/byPetugas', 'API\V1\PemeriksaanIbuHamilController@storePemeriksaanbyPegawai');
    Route::post('destroy/pemeriksaan-ibuhamil/{id}','API\V1\PemeriksaanIbuHamilController@destroyPemeriksaanIbuHamil');

    Route::apiResource('jadwal-pemeriksaan', API\V1\JadwalPemeriksaanController::class);

    Route::apiResource('detail-pemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);

    Route::post('cek-berat-boys', 'API\V1\PemeriksaanBalitaController@checkWeightBoys');
    Route::post('cek-berat-girls', 'API\V1\PemeriksaanBalitaController@checkWeightGirls');
    Route::post('cek-tinggi-boys', 'API\V1\PemeriksaanBalitaController@checkHeightBoys');
    Route::post('cek-tinggi-girls', 'API\V1\PemeriksaanBalitaController@checkHeightGirls');
    Route::post('cek-kepala-boys', 'API\V1\PemeriksaanBalitaController@checkHeadBoys');
    Route::post('cek-kepala-girls', 'API\V1\PemeriksaanBalitaController@checkHeadGirls');
    Route::post('cek-lengan-boys', 'API\V1\PemeriksaanBalitaController@checkArmBoys');
    Route::post('cek-lengan-girls', 'API\V1\PemeriksaanBalitaController@checkArmGirls');

    Route::get('me/petugas', 'API\V1\PetugasKesehatanController@showMyPetugas');
    Route::post('me/update-petugas', 'API\V1\PetugasKesehatanController@updateMyPetugas');

    Route::apiResource('operator_posyandu', API\V1\OperatorPosyanduController::class);
    Route::get('fetch-operator-posyandu', 'API\V1\OperatorPosyanduController@fetchUser');

    Route::post('cek-berat-ibu-hamil', 'API\V1\PemeriksaanIbuHamilController@cekBeratIbuHamil');
    Route::get('data-grafik-ibu-hamil/{id}', 'API\V1\PemeriksaanIbuHamilController@getIbuHamilByUsiaKandungan');

    Route::get('cek-imunisasi-balita/{id}', 'API\V1\PemeriksaanBalitaController@cekVaksinBalita');

    Route::get('petugas/with-pemeriksaan-balita', 'API\V1\PemeriksaanBalitaController@showPemeriksaanBalitaForPetugas');
    Route::get('petugas/with-pemeriksaan-ibu-hamil', 'API\V1\PemeriksaanIbuHamilController@showPemeriksaanIbuHamilForPetugas');

    Route::apiResource('dokter', API\V1\DokterController::class);

 
});