<?php

use App\Http\Controllers\FnbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemilihController;
use App\Http\Controllers\BantuanMasyarakatController;
use App\Http\Controllers\BantuanTokohController;
use App\Http\Controllers\DukunganTokohController;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            // api secure

        });
    });
});

// Pemilih
Route::group([
    'prefix' => 'pemilih'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list-by-relawan/{id}', [PemilihController::class, 'listPemilihByRelawan']);
    Route::get('list-relawan', [PemilihController::class, 'listRelawan']);
    Route::post('import', [PemilihController::class, 'importDataPemilih']);
    Route::delete('delete-relawan/{id}', [PemilihController::class, 'deleteRelawan']);
    // });
});

// Bantuan Masyarakat
Route::group([
    'prefix' => 'bantuan-masyarakat'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list', [BantuanMasyarakatController::class, 'listDataBantuanMasyarakat']);
    Route::get('detail/{id}', [BantuanMasyarakatController::class, 'detailDataBantuanMasyarakat']);
    Route::post('import', [BantuanMasyarakatController::class, 'importDataBantuanMasyarakat']);
    Route::post('input', [BantuanMasyarakatController::class, 'inputDataBantuanMasyarakat']);
    Route::post('update/{id}', [BantuanMasyarakatController::class, 'updateDataBantuanMasyarakat']);
    Route::delete('delete/{id}', [BantuanMasyarakatController::class, 'deleteDataBantuanMasyarakat']);
    // });
});

// Bantuan Tokoh
Route::group([
    'prefix' => 'bantuan-tokoh'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list', [BantuanTokohController::class, 'listDataBantuanTokoh']);
    Route::get('detail/{id}', [BantuanTokohController::class, 'detailDataBantuanTokoh']);
    Route::post('import', [BantuanTokohController::class, 'importDataBantuanTokoh']);
    Route::post('input', [BantuanTokohController::class, 'inputDataBantuanTokoh']);
    Route::post('update/{id}', [BantuanTokohController::class, 'updateDataBantuanTokoh']);
    Route::delete('delete/{id}', [BantuanTokohController::class, 'deleteDataBantuanTokoh']);
    // });
});

// Dukungan Tokoh
Route::group([
    'prefix' => 'dukungan-tokoh'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list', [DukunganTokohController::class, 'listDataDukunganTokoh']);
    Route::get('detail/{id}', [DukunganTokohController::class, 'detailDataDukunganTokoh']);
    Route::post('import', [DukunganTokohController::class, 'importDataDukunganTokoh']);
    Route::post('input', [DukunganTokohController::class, 'inputDataDukunganTokoh']);
    Route::post('update/{id}', [DukunganTokohController::class, 'updateDataDukunganTokoh']);
    Route::delete('delete/{id}', [DukunganTokohController::class, 'deleteDataDukunganTokoh']);
    // });
});
