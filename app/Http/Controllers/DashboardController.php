<?php

namespace App\Http\Controllers;

use App\Models\bantuan_masyarakat;
use App\Models\bantuan_relawan;
use App\Models\bantuan_tokoh;
use App\Models\dukungan_tokoh;
use App\Models\MasterDataDpt;
use App\Models\MasterDataWarga;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function getBantuanRelawanAvailableYears()
    // {
    //     $years = bantuan_relawan::selectRaw('YEAR(tanggal) as year')
    //         ->groupBy('year')
    //         ->orderBy('year', 'desc')
    //         ->pluck('year')
    //         ->filter(function ($year) {
    //             return !is_null($year);
    //         });

    //     return response()->json(['message' => 'success', 'data' => $years]);
    // }

    // public function getBantuanMasyarakatAvailableYears()
    // {
    //     $years = bantuan_masyarakat::selectRaw('YEAR(tanggal) as year')
    //         ->groupBy('year')
    //         ->orderBy('year', 'desc')
    //         ->pluck('year')
    //         ->filter(function ($year) {
    //             return !is_null($year);
    //         });

    //     return response()->json(['message' => 'success', 'data' => $years]);
    // }

    // public function getBantuanTokohAvailableYears()
    // {
    //     $years = bantuan_tokoh::selectRaw('YEAR(tanggal) as year')
    //         ->groupBy('year')
    //         ->orderBy('year', 'desc')
    //         ->pluck('year')
    //         ->filter(function ($year) {
    //             return !is_null($year);
    //         });

    //     return response()->json(['message' => 'success', 'data' => $years]);
    // }

    // public function getDukunganTokohAvailableYears()
    // {
    //     $years = dukungan_tokoh::selectRaw('YEAR(tanggal) as year')
    //         ->groupBy('year')
    //         ->orderBy('year', 'desc')
    //         ->pluck('year')
    //         ->filter(function ($year) {
    //             return !is_null($year);
    //         });

    //     return response()->json(['message' => 'success', 'data' => $years]);
    // }

    public function dashboardDataByKabupaten($id)
    {
        // Get all kecamatan based on kabupaten_id
        $dptData = MasterKabupaten::get();
        $nikData = MasterDataWarga::where('id_kabupaten', $id)->count();

        return response()->json([
            'message' => 'success',
            'data' => [
                'dpt' => $dptData,
                'warga' => $nikData
            ]
        ]);
    }

    public function listBarChartByKabupaten($id)
    {
        // Get all kecamatan based on kabupaten_id
        $kecamatanList = MasterKecamatan::where('kabupaten_id', $id)->get();

        // Get warga data grouped by kecamatan
        $wargaData = MasterDataWarga::selectRaw('id_kecamatan, COUNT(*) as count')
            ->where('id_kabupaten', $id)
            ->groupBy('id_kecamatan')
            ->get()
            ->keyBy('id_kecamatan'); // Key by id_kecamatan for easy lookup

        // Map through each kecamatan and attach warga count or set to 0 if not available
        $kecamatanData = $kecamatanList->map(function ($kecamatan) use ($wargaData) {
            return [
                'kecamatan_name' => $kecamatan->name,
                'count' => $wargaData->get($kecamatan->id)->count ?? 0, // If no warga, count = 0
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $kecamatanData
        ]);
    }

    // public function listLineChartDataDptByKabupaten(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = MasterDataDpt::selectRaw('MONTH(created_at) as month, id_kabupaten, COUNT(*) as count')
    //         ->whereYear('created_at', $year)
    //         ->groupBy('month', 'id_kabupaten')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataWargaByKabupaten(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = MasterDataWarga::selectRaw('MONTH(created_at) as month, id_kabupaten, COUNT(*) as count')
    //         ->whereYear('created_at', $year)
    //         ->groupBy('month', 'id_kabupaten')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataBantuanRelawanBySasaran(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_relawan::selectRaw('MONTH(created_at) as month, sasaran, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->groupBy('month', 'sasaran')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataBantuanRelawanByJenisBantuan(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_relawan::selectRaw('MONTH(created_at) as month, jenis_bantuan, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->groupBy('month', 'jenis_bantuan')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataBantuanMasyarakat(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::selectRaw('MONTH(created_at) as month, jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->groupBy('month', 'jenis_barang')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    public function listPieChartDataDptRelawanByKabupaten(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = MasterDataDpt::selectRaw('id_kabupaten, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('id_kabupaten')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }

    public function listPieChartDataWargaRelawanByKabupaten(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = MasterDataWarga::selectRaw('id_kabupaten, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('id_kabupaten')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }

    // public function listPieChartDataBantuanRelawanBySasaran(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_relawan::selectRaw('sasaran, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->whereMonth('tanggal', $month)
    //         ->groupBy('sasaran')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listPieChartDataBantuanRelawanByJenisBantuan(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_relawan::selectRaw('jenis_bantuan, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->whereMonth('tanggal', $month)
    //         ->groupBy('jenis_bantuan')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listPieChartDataBantuanMasyarakat(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::selectRaw('jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->whereMonth('tanggal', $month)
    //         ->groupBy('jenis_barang')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataBantuanTokoh(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::join('jenis_barangs', 'bantuan_tokohs.id', '=', 'jenis_barangs.bantuan_tokoh_id')
    //         ->selectRaw('MONTH(created_at) as month, jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->groupBy('month', 'jenis_barang')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listPieChartDataBantuanTokoh(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::join('jenis_barangs', 'bantuan_tokohs.id', '=', 'jenis_barangs.bantuan_tokoh_id')
    //         ->selectRaw('jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->whereMonth('tanggal', $month)
    //         ->groupBy('jenis_barang')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listLineChartDataDukunganTokoh(Request $request)
    // {
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::join('jenis_dukungans', 'dukungan_tokohs.id', '=', 'jenis_dukungans.dukungan_tokoh_id')
    //         ->selectRaw('MONTH(created_at) as month, jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->groupBy('month', 'jenis_barang')
    //         ->orderBy('month')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }

    // public function listPieChartDataDukunganTokoh(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     $data = bantuan_masyarakat::join('jenis_dukungans', 'dukungan_tokohs.id', '=', 'jenis_dukungans.dukungan_tokoh_id')
    //         ->selectRaw('jenis_barang, COUNT(*) as count')
    //         ->whereYear('tanggal', $year)
    //         ->whereMonth('tanggal', $month)
    //         ->groupBy('jenis_barang')
    //         ->get();

    //     return response()->json(['message' => 'success', 'data' => $data]);
    // }
}
