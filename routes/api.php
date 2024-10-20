<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalisaController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\DataDptController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\DashboardController;

use App\Exports\DataLokasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

Route::get('/export-lokasi', function () {
    // Menjalankan query untuk mendapatkan data yang akan diekspor
    $data = DB::table('master_kelurahans AS kel')
        ->join('master_kecamatans AS kec', 'kel.kecamatan_id', '=', 'kec.id')
        ->join('master_kabupatens AS kab', 'kec.kabupaten_id', '=', 'kab.id')
        ->select('kab.name AS kabupaten', 'kec.name AS kecamatan', 'kel.name AS kelurahan')
        ->get();

    return Excel::download(new DataLokasiExport($data), 'data_lokasi.xlsx');
});


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


Route::prefix('lokasi')->group(function () {
    Route::get('/kabupaten', [LokasiController::class, 'listKabupaten']);
    Route::get('/kecamatan/{id}', [LokasiController::class, 'listKecamatan']);
    Route::get('/kelurahan/{id}', [LokasiController::class, 'listKelurahan']);
});

Route::prefix('analisa')->group(function () {
    Route::get('/list-kabupaten', [AnalisaController::class, 'listKabupaten']);
    Route::get('/list-kecamatan/{id}', [AnalisaController::class, 'listKecamatanByKabupaten']);
    Route::get('/list-kelurahan/{id}', [AnalisaController::class, 'listKelurahanByKecamatan']);
});

Route::group([
    'prefix' => 'warga'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('/export-bansos', [DataWargaController::class, 'exportBansosPdf']);       // Get specific warga by ID
        Route::get('/bansos', [DataWargaController::class, 'listBansos']);       // Get specific warga by ID
        Route::post('/import', [DataWargaController::class, 'importDataWarga']);
        Route::get('/list', [DataWargaController::class, 'listDataWarga']);          // Get all data warga
        Route::get('/list/{id}', [DataWargaController::class, 'listDataWargaByPj']);          // Get all data warga
        // Route::post('/', [DataWargaController::class, 'store']);         // Create new warga
        // Route::get('/{id}', [DataWargaController::class, 'show']);       // Get specific warga by ID
        // Route::put('/{id}', [DataWargaController::class, 'update']);     // Update warga by ID
        Route::delete('/{id}', [DataWargaController::class, 'destroy']); // Delete warga by ID
        Route::post('/import-bansos', [DataWargaController::class, 'importDataPenerimaBansos']);
    });
});


Route::prefix('dpt')->group(function () {
    Route::post('/import', [DataDptController::class, 'importDataDpt']);
    Route::get('/', [DataDptController::class, 'index']);          // Get all data DPT
    Route::post('/', [DataDptController::class, 'store']);         // Create new DPT
    Route::get('/{id}', [DataDptController::class, 'show']);       // Get specific DPT by ID
    Route::put('/{id}', [DataDptController::class, 'update']);     // Update DPT by ID
    Route::delete('/{id}', [DataDptController::class, 'destroy']); // Delete DPT by ID
});




// Dashboard
Route::group([
    'prefix' => 'dashboard'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    // Route::get('/br-available-years', [DashboardController::class, 'getBantuanRelawanAvailableYears']);
    Route::get('/dashboard-by-kabupaten/{id}', [DashboardController::class, 'dashboardDataByKabupaten']);
    Route::get('/bar-chart-by-kabupaten/{id}', [DashboardController::class, 'listBarChartByKabupaten']);
    // Route::get('/br-list-line-by-sasaran', [DashboardController::class, 'listLineChartDataBantuanRelawanBySasaran']);
    // Route::get('/br-list-line-by-jenis-bantuan', [DashboardController::class, 'listLineChartDataBantuanRelawanByJenisBantuan']);
    // Route::get('/br-list-pie-by-sasaran', [DashboardController::class, 'listPieChartDataBantuanRelawanBySasaran']);
    // Route::get('/br-list-pie-by-jenis-bantuan', [DashboardController::class, 'listPieChartDataBantuanRelawanByJenisBantuan']);
    // });
});
