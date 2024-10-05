<?php

namespace App\Http\Controllers;

use App\Models\bantuan_masyarakat;
use App\Models\bantuan_tokoh;
use App\Models\dukungan_tokoh;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getBantuanMasyarakatAvailableYears()
    {
        $years = bantuan_masyarakat::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($year) {
                return !is_null($year);
            });

        return response()->json(['message' => 'success', 'data' => $years]);
    }

    public function getBantuanTokohAvailableYears()
    {
        $years = bantuan_tokoh::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($year) {
                return !is_null($year);
            });

        return response()->json(['message' => 'success', 'data' => $years]);
    }

    public function getDukunganTokohAvailableYears()
    {
        $years = dukungan_tokoh::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(function ($year) {
                return !is_null($year);
            });

        return response()->json(['message' => 'success', 'data' => $years]);
    }

    public function listLineChartDataBantuanMasyarakat(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $data = bantuan_masyarakat::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }

    public function listPieChartDataBantuanMasyarakat(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = bantuan_masyarakat::selectRaw('COUNT(*) as count')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            // ->groupBy('status_pengajuan')
            ->get();

        return response()->json(['message' => 'success', 'data' => $data]);
    }
}
