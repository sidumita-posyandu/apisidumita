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
    Route::get('me', 'AuthController@me');
});


Route::apiResource('provinsi', API\V1\MasterData\ProvinsiController::class);
Route::apiResource('kabupaten', API\V1\MasterData\KabupatenController::class);
Route::apiResource('kecamatan', API\V1\MasterData\KecamatanController::class);
Route::apiResource('desa', API\V1\MasterData\DesaController::class);
Route::apiResource('dusun', API\V1\MasterData\DusunController::class);

Route::middleware(['jwt.verify'])->group(function () {
    Route::apiResource('user', API\V1\UserController::class);
    Route::apiResource('role', API\V1\RoleController::class);

    Route::post('fetch-provinsi', 'API\V1\MasterData\KabupatenController@fetchProvinsi');
    Route::post('fetch-kabupaten', 'API\V1\MasterData\KecamatanController@fetchKabupaten');
    Route::post('fetch-kecamatan', 'API\V1\MasterData\DesaController@fetchKecamatan');
    Route::post('fetch-desa', 'API\V1\MasterData\DusunController@fetchDesa');

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

    Route::apiResource('balita', API\V1\BalitaController::class);
    Route::get('me/balita', 'API\V1\BalitaController@showMyBalitas');
    Route::apiResource('ibu-hamil', API\V1\IbuHamilController::class);
    Route::get('me/ibu-hamil', 'API\V1\IbuHamilController@showMyIbuHamils');
    Route::apiResource('vitamin', API\V1\VitaminController::class);
    Route::apiResource('vaksin', API\V1\VaksinController::class);

    Route::apiResource('pemeriksaan-balita', API\V1\PemeriksaanBalitaController::class);
    Route::apiResource('detailpemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);

    Route::get('pemeriksaan-balita/balita/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByBalita');
    Route::get('detailpemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanByBalita');
    Route::get('pemeriksaan-balita/latest-balita/{id}','API\V1\PemeriksaanBalitaController@getTwoLastPemeriksaanByBalita');
    Route::get('pemeriksaan-balita/umur/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByUmur');

    Route::apiResource('pemeriksaan-ibuhamil', API\V1\PemeriksaanIbuHamilController::class);

    Route::get('pemeriksaan-ibuhamil/ibuhamil/{id}', 'API\V1\PemeriksaanIbuHamilController@getPemeriksaanByIbuHamil');
    Route::get('pemeriksaan-ibuhamil/latest-ibuhamil/{id}','API\V1\PemeriksaanIbuHamilController@getTwoLastPemeriksaanByIbuHamil');

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

    Route::apiResource('operator_posyandu', API\V1\OperatorPosyanduController::class);
});