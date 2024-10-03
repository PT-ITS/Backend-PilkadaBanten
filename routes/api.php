<?php

use App\Http\Controllers\FnbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HiburanController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ImportDataController;
use App\Http\Controllers\RekapitulasiController;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('import', [AuthController::class, 'import']);
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('list-pengguna', [AuthController::class, 'listPengguna']);
        Route::get('list-pengelola', [AuthController::class, 'listPengelola']);
        Route::post('update/{id}', [AuthController::class, 'update']);
        Route::delete('delete/{id}', [AuthController::class, 'delete']);


        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            // api secure

        });
    });
});

// All
Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::get('get-data-dashboard', [DashboardController::class, 'getDataDashboard']);
    Route::get('get-data-dashboard/{id}', [DashboardController::class, 'dashboardUsaha']);
    Route::get('list-all', [DashboardController::class, 'listAll']);
    Route::get('export', [DashboardController::class, 'export']);
});

// Hotel
Route::group([
    'prefix' => 'hotel'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list-hotel', [HotelController::class, 'listDataHotel']);
        Route::get('detail-hotel/{id}', [HotelController::class, 'detailDataHotel']);
        Route::post('input-hotel', [HotelController::class, 'inputDataHotelAndKaryawan']);
        Route::post('update-hotel/{id}', [HotelController::class, 'updateDataHotel']);
        Route::delete('delete-hotel/{id}', [HotelController::class, 'deleteDataHotel']);
        Route::post('validate-hotel/{id}', [HotelController::class, 'validateDataHotel']);
    });
});

// Hiburan
Route::group([
    'prefix' => 'hiburan'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list-hiburan', [HiburanController::class, 'listHiburan']);
        Route::get('detail-hiburan/{id}', [HiburanController::class, 'detailHiburan']);
        Route::post('input-hiburan', [HiburanController::class, 'inputHiburanAndKaryawan']);
        Route::post('update-hiburan/{id}', [HiburanController::class, 'updateHiburan']);
        Route::delete('delete-hiburan/{id}', [HiburanController::class, 'deleteHiburan']);
        Route::post('validate-hiburan/{id}', [HiburanController::class, 'validateHiburan']);
    });
});

// FnB
Route::group([
    'prefix' => 'fnb'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list-fnb', [FnbController::class, 'listFnb']);
        Route::get('detail-fnb/{id}', [FnbController::class, 'detailFnb']);
        Route::post('input-fnb', [FnbController::class, 'inputFnbAndKaryawan']);
        Route::post('update-fnb/{id}', [FnbController::class, 'updateFnb']);
        Route::delete('delete-fnb/{id}', [FnbController::class, 'deleteFnb']);
        Route::post('validate-fnb/{id}', [FnbController::class, 'validateFnb']);
    });
});

// Karyawan
Route::group([
    'prefix' => 'karyawan'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list-karyawan-hotel/{id}', [KaryawanController::class, 'listKaryawanHotel']);
        Route::get('list-karyawan-hiburan/{id}', [KaryawanController::class, 'listKaryawanHiburan']);
        Route::get('list-karyawan-fnb/{id}', [KaryawanController::class, 'listKaryawanFnb']);
        Route::post('import-karyawan-hotel/{id}', [KaryawanController::class, 'importKaryawanHotel']);
        Route::post('import-karyawan-hiburan/{id}', [KaryawanController::class, 'importKaryawanHiburan']);
        Route::post('import-karyawan-fnb/{id}', [KaryawanController::class, 'importKaryawanFnb']);
        Route::post('input-karyawan-hotel', [KaryawanController::class, 'inputDataKaryawanHotel']);
        Route::post('input-karyawan-hiburan', [KaryawanController::class, 'inputDataKaryawanHiburan']);
        Route::post('input-karyawan-fnb', [KaryawanController::class, 'inputDataKaryawanFnb']);
        Route::post('update-karyawan/{id}', [KaryawanController::class, 'updateDataKaryawan']);
        Route::delete('delete-karyawan/{id}', [KaryawanController::class, 'deleteDataKaryawan']);
    });
});


// Log
Route::get('/log', [DashboardController::class, 'log']);
Route::get('/rekapitulasi', [RekapitulasiController::class, 'rekapitulasi']);
