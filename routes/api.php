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



Route::middleware(['jwt.verify'])->group(function () {
    Route::apiResource('user', API\V1\UserController::class);
    Route::apiResource('role', API\V1\RoleController::class);

    Route::apiResource('provinsi', API\V1\MasterData\ProvinsiController::class);
    Route::apiResource('kabupaten', API\V1\MasterData\KabupatenController::class);
    Route::apiResource('kecamatan', API\V1\MasterData\KecamatanController::class);
    Route::apiResource('desa', API\V1\MasterData\DesaController::class);
    Route::apiResource('dusun', API\V1\MasterData\DusunController::class);

    Route::get('fetch-provinsi/{id}', 'API\V1\MasterData\KabupatenController@fetchProvinsi');
    Route::get('fetch-kabupaten/{id}', 'API\V1\MasterData\KecamatanController@fetchKabupaten');
    Route::get('fetch-kecamatan/{id}', 'API\V1\MasterData\DesaController@fetchKecamatan');
    Route::get('fetch-desa/{id}', 'API\V1\MasterData\DusunController@fetchDesa');

    Route::apiResource('keluarga', API\V1\KeluargaController::class);
    Route::apiResource('detail-keluarga', API\V1\DetailKeluargaController::class);
    Route::get('umur/{id}', 'API\V1\DetailKeluargaController@getUmur');
    Route::get('me/keluarga', 'API\V1\KeluargaController@showMyKeluarga');
    Route::get('me/balitas-from-keluarga', 'API\V1\DetailKeluargaController@showMyBalitas');
    Route::get('me/ibuhamils-from-keluarga', 'API\V1\DetailKeluargaController@showMyIbuHamils');
    Route::post('me/create-detail-keluarga', 'API\V1\DetailKeluargaController@storeMyDetKeluarga');
    Route::post('me/create-keluarga', 'API\V1\KeluargaController@storeMyKeluarga');
    Route::post('me/update-keluarga', 'API\V1\KeluargaController@updateMyKeluarga');

    Route::apiResource('balita', API\V1\BalitaController::class);
    Route::apiResource('ibu-hamil', API\V1\IbuHamilController::class);
    Route::apiResource('vitamin', API\V1\VitaminController::class);
    Route::apiResource('vaksin', API\V1\VaksinController::class);

    Route::apiResource('pemeriksaan-balita', API\V1\PemeriksaanBalitaController::class);
    Route::apiResource('detailpemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);

    Route::get('pemeriksaan-balita/balita/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByBalita');
    Route::get('detailpemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanByBalita');
    Route::get('pemeriksaan-balita/latest-balita/{id}','API\V1\PemeriksaanBalitaController@getTwoLastPemeriksaanByBalita');

    Route::apiResource('pemeriksaan-ibuhamil', API\V1\PemeriksaanIbuHamilController::class);

    Route::get('pemeriksaan-ibuhamil/ibuhamil/{id}', 'API\V1\PemeriksaanIbuHamilController@getPemeriksaanByIbuHamil');
    Route::get('pemeriksaan-ibuhamil/latest-ibuhamil/{id}','API\V1\PemeriksaanIbuHamilController@getTwoLastPemeriksaanByIbuHamil');

    Route::apiResource('jadwal-pemeriksaan', API\V1\JadwalPemeriksaanController::class);

    Route::apiResource('detail-pemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);
});