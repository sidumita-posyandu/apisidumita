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
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::apiResource('provinsi', API\V1\MasterData\ProvinsiController::class);
Route::apiResource('kabupaten', API\V1\MasterData\KabupatenController::class);
Route::apiResource('kecamatan', API\V1\MasterData\KecamatanController::class);
Route::apiResource('desa', API\V1\MasterData\DesaController::class);
Route::apiResource('dusun', API\V1\MasterData\DusunController::class);

Route::apiResource('keluarga', API\V1\KeluargaController::class);
Route::apiResource('detail-keluarga', API\V1\DetailKeluargaController::class);

Route::apiResource('balita', API\V1\BalitaController::class);
Route::apiResource('ibu-hamil', API\V1\IbuHamilController::class);
Route::apiResource('vitamin', API\V1\VitaminController::class);
Route::apiResource('vaksin', API\V1\VaksinController::class);


Route::apiResource('pemeriksaan-balita', API\V1\PemeriksaanBalitaController::class);
Route::get('pemeriksaan-balita/balita/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByBalita');
Route::get('detailpemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanByBalita');
Route::get('pemeriksaan-balita/latest-balita/{id}','API\V1\PemeriksaanBalitaController@getTwoLastPemeriksaanByBalita');

Route::apiResource('pemeriksaan-ibuhamil', API\V1\PemeriksaanIbuHamilController::class);

Route::get('pemeriksaan-balita/balita/{id}', 'API\V1\PemeriksaanBalitaController@getPemeriksaanByBalita');
Route::get('detailpemeriksaan-balita/{id}','API\V1\PemeriksaanBalitaController@getDetailPemeriksaanByBalita');
Route::get('pemeriksaan-balita/latest-balita/{id}','API\V1\PemeriksaanBalitaController@getTwoLastPemeriksaanByBalita');

Route::apiResource('jadwal-pemeriksaan', API\V1\JadwalPemeriksaanController::class);

Route::apiResource('detail-pemeriksaan-balita', API\V1\DetailPemeriksaanBalitaController::class);