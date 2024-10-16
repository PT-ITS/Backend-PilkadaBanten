<?php

use App\Http\Controllers\FnbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalisaController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\DataDptController;
use App\Http\Controllers\LokasiController;

// usang
use App\Http\Controllers\PemilihController;
use App\Http\Controllers\BantuanMasyarakatController;
use App\Http\Controllers\BantuanPemilihController;
use App\Http\Controllers\BantuanRtController;
use App\Http\Controllers\BantuanRwController;
use App\Http\Controllers\BantuanPemukaAgamaController;
use App\Http\Controllers\BantuanRelawanController;
use App\Http\Controllers\BantuanTokohController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DukunganTokohController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\PemukaAgamaController;

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
    Route::get('/bar-chart-by-kabupaten', [DashboardController::class, 'listBarChartByKabupaten']);
    // Route::get('/br-list-line-by-sasaran', [DashboardController::class, 'listLineChartDataBantuanRelawanBySasaran']);
    // Route::get('/br-list-line-by-jenis-bantuan', [DashboardController::class, 'listLineChartDataBantuanRelawanByJenisBantuan']);
    // Route::get('/br-list-pie-by-sasaran', [DashboardController::class, 'listPieChartDataBantuanRelawanBySasaran']);
    // Route::get('/br-list-pie-by-jenis-bantuan', [DashboardController::class, 'listPieChartDataBantuanRelawanByJenisBantuan']);
    // });
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
    Route::post('import-by-relawan', [PemilihController::class, 'importPemilihByRelawan']);
    Route::delete('delete-pemilih/{id}', [PemilihController::class, 'deletePemilih']);
    Route::delete('delete-relawan/{id}', [PemilihController::class, 'deleteRelawan']);
    // });
});

// Bantuan Relawan
Route::group([
    'prefix' => 'bantuan-relawan'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('export-all', [BantuanRelawanController::class, 'exportAllBantuanRelawan']);
    Route::get('export-by-relawan/{id}', [BantuanRelawanController::class, 'exportBantuanRelawanByRelawan']);
    Route::get('info/{id}', [BantuanRelawanController::class, 'infoBantuanByRelawan']);
    Route::get('sasaran', [BantuanRelawanController::class, 'infoJumlahSasaranBantuan']);
    Route::get('jenis', [BantuanRelawanController::class, 'infoJumlahJenisBantuan']);
    Route::get('list/{id}', [BantuanRelawanController::class, 'listBantuanRelawanByRelawan']);
    Route::post('import', [BantuanRelawanController::class, 'importBantuanRelawanByRelawan']);
    Route::post('input', [BantuanRelawanController::class, 'createBantuanByRelawan']);
    Route::post('update/{id}', [BantuanRelawanController::class, 'updateBantuanRelawan']);
    Route::delete('delete/{id}', [BantuanRelawanController::class, 'deleteBantuanRelawan']);
    // });
});

// Bantuan Pemilih
// Route::group([
//     'prefix' => 'bantuan-pemilih'
// ], function () {
//     // Route::group([
//     //     'middleware' => 'auth:api'
//     // ], function () {
//     Route::get('list/{id}', [BantuanPemilihController::class, 'listBantuanPemilihByRelawan']);
//     Route::post('import', [BantuanPemilihController::class, 'importBantuanPemilihByRelawan']);
//     Route::post('update/{id}', [BantuanPemilihController::class, 'updateBantuanPemilih']);
//     Route::delete('delete/{id}', [BantuanPemilihController::class, 'deleteBantuanPemilih']);
//     // });
// });

// Rt
Route::group([
    'prefix' => 'rt'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list/{id}', [RtController::class, 'listRt']);
    Route::post('import', [RtController::class, 'importRt']);
    Route::post('update/{id}', [RtController::class, 'updateRt']);
    Route::delete('delete/{id}', [RtController::class, 'deleteRt']);
    // });
});

// Bantuan Rt
// Route::group([
//     'prefix' => 'bantuan-rt'
// ], function () {
//     // Route::group([
//     //     'middleware' => 'auth:api'
//     // ], function () {
//     Route::get('list/{id}', [BantuanRtController::class, 'listBantuanRtByRt']);
//     Route::post('import', [BantuanRtController::class, 'importBantuanRtByRt']);
//     Route::post('update/{id}', [BantuanRtController::class, 'updateBantuanRt']);
//     Route::delete('delete/{id}', [BantuanRtController::class, 'deleteBantuanRt']);
//     // });
// });

// Rw
Route::group([
    'prefix' => 'rw'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list/{id}', [RwController::class, 'listRw']);
    Route::post('import', [RwController::class, 'importRw']);
    Route::post('update/{id}', [RwController::class, 'updateRw']);
    Route::delete('delete/{id}', [RwController::class, 'deleteRw']);
    // });
});

// Bantuan Rw
// Route::group([
//     'prefix' => 'bantuan-rw'
// ], function () {
//     // Route::group([
//     //     'middleware' => 'auth:api'
//     // ], function () {
//     Route::get('list/{id}', [BantuanRwController::class, 'listBantuanRwByRw']);
//     Route::post('import', [BantuanRwController::class, 'importBantuanRwByRw']);
//     Route::post('update/{id}', [BantuanRwController::class, 'updateBantuanRw']);
//     Route::delete('delete/{id}', [BantuanRwController::class, 'deleteBantuanRw']);
//     // });
// });

// Pemuka Agama
Route::group([
    'prefix' => 'pemuka-agama'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list/{id}', [PemukaAgamaController::class, 'listPemukaAgama']);
    Route::post('import', [PemukaAgamaController::class, 'importPemukaAgama']);
    Route::post('update/{id}', [PemukaAgamaController::class, 'updatePemukaAgama']);
    Route::delete('delete/{id}', [PemukaAgamaController::class, 'deletePemukaAgama']);
    // });
});

// Bantuan Pemuka Agama
// Route::group([
//     'prefix' => 'bantuan-pemuka-agama'
// ], function () {
//     // Route::group([
//     //     'middleware' => 'auth:api'
//     // ], function () {
//     Route::get('list/{id}', [BantuanPemukaAgamaController::class, 'listBantuanPemukaAgamaByPemukaAgama']);
//     Route::post('import', [BantuanPemukaAgamaController::class, 'importBantuanPemukaAgamaByPemukaAgama']);
//     Route::post('update/{id}', [BantuanPemukaAgamaController::class, 'updateBantuanPemukaAgama']);
//     Route::delete('delete/{id}', [BantuanPemukaAgamaController::class, 'deleteBantuanPemukaAgama']);
//     // });
// });

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
