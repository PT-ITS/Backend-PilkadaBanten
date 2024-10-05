<?php

use App\Http\Controllers\FnbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BantuanMasyarakatController;

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

// Hotel
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
