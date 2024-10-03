<?php

namespace App\Http\Controllers;

use App\Exports\WisataExport;
use App\Models\Hotel;
use App\Models\Hiburan;
use App\Models\Fnb;
use App\Models\KaryawanHotel;
use App\Models\KaryawanHiburan;
use App\Models\KaryawanFnb;
use Illuminate\Http\Request;
use App\Http\Services\DashboardService;
use App\Models\Karyawan;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getDataDashboard()
    {
        $result = $this->dashboardService->getDataDashboard();
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function listAll()
    {
        // cek jika user login
        $user = auth()->user();

        if (!$user) {
            // return jika belum login
            return response()->json(
                [
                    'message' => 'Unauthorized',
                    'data' => []
                ],
                401
            );
        }

        // inisialisasi hasil
        $result = [];

        // cek level user
        if ($user->level == 1) { // jika admin
            $result = $this->dashboardService->listAll();
        } else if ($user->level == 2) {
            $result = $this->dashboardService->listAllByPengelola();
        } else { // jika surveyor
            // $result = $this->dashboardService->listAllBySurveyor();
            $result = $this->dashboardService->listAll();
        }

        // return hasil
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function export()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Unauthorized',
                    'data' => []
                ],
                401
            );
        }

        $result = [];

        if ($user->level == 1) {
            $result = $this->dashboardService->exportAll();
        } else if ($user->level == 2) {
            $result = $this->dashboardService->listAllByPengelola();
        } else {
            $result = $this->dashboardService->exportAll();
        }

        return Excel::download(new WisataExport($result['data']), 'data_wisata_export.xlsx');
    }

    public function log()
    {
        $hotels = Hotel::join('users', 'hotels.surveyor_id', '=', 'users.id')
            ->select('hotels.*', 'users.name as surveyor_name', 'users.email as surveyor_email')
            ->get();

        $hiburans = Hiburan::join('users', 'hiburans.surveyor_id', '=', 'users.id')
            ->select('hiburans.*', 'users.name as surveyor_name', 'users.email as surveyor_email')
            ->get();

        $fn_b_s = Fnb::join('users', 'fn_b_s.surveyor_id', '=', 'users.id')
            ->select('fn_b_s.*', 'users.name as surveyor_name', 'users.email as surveyor_email')
            ->get();

        return response()->json([
            'message' => 'Data log berhasil diambil.',
            'hotel' => $hotels,
            'hiburan' => $hiburans,
            'fnb' => $fn_b_s,
        ]);
    }

    public function dashboardUsaha($id)
    {
        $dataHotel = Hotel::where('pj_id', $id)->first();
        $dataHiburan = Hiburan::where('pj_id', $id)->first();
        $dataFnb = Fnb::where('pj_id', $id)->first();

        if ($dataHotel) {
            $dataKaryawan = KaryawanHotel::where('hotel_id', $dataHotel->id)->get();
            // Mengambil ID karyawan dari hasil query di atas
            $karyawanIds = $dataKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $dataKaryawanPria = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $dataKaryawanWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();
            $karyawanSd = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SD/MI')->count();
            $karyawanSmp = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMP/MTS')->count();
            $karyawanSma = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMA/SMK/MA')->count();
            $karyawanS1 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'D3/S1/D4')->count();
            $karyawanS2 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S2')->count();
            $karyawanS3 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S3')->count();
            $karyawanWni = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNI')->count();
            $karyawanWna = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNA')->count();
            $dataDashboard = [
                "jumlahKaryawanPria" => $dataKaryawanPria,
                "jumlahKaryawanWanita" => $dataKaryawanWanita,
                "karyawanSd" => $karyawanSd,
                "karyawanSmp" => $karyawanSmp,
                "karyawanSma" => $karyawanSma,
                "karyawanS1" => $karyawanS1,
                "karyawanS2" => $karyawanS2,
                "karyawanS3" => $karyawanS3,
                "karyawanWni" => $karyawanWni,
                "karyawanWna" => $karyawanWna,
            ];
            return response()->json(['message' => 'success', 'data' => $dataDashboard]);
        } else if ($dataHiburan) {
            $dataKaryawan = KaryawanHiburan::where('hiburan_id', $dataHiburan->id)->get();
            $karyawanIds = $dataKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $dataKaryawanPria = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $dataKaryawanWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();
            $karyawanSd = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SD/MI')->count();
            $karyawanSmp = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMP/MTS')->count();
            $karyawanSma = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMA/SMK/MA')->count();
            $karyawanS1 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'D3/S1/D4')->count();
            $karyawanS2 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S2')->count();
            $karyawanS3 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S3')->count();
            $karyawanWni = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNI')->count();
            $karyawanWna = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNA')->count();
            $dataDashboard = [
                "jumlahKaryawanPria" => $dataKaryawanPria,
                "jumlahKaryawanWanita" => $dataKaryawanWanita,
                "karyawanSd" => $karyawanSd,
                "karyawanSmp" => $karyawanSmp,
                "karyawanSma" => $karyawanSma,
                "karyawanS1" => $karyawanS1,
                "karyawanS2" => $karyawanS2,
                "karyawanS3" => $karyawanS3,
                "karyawanWni" => $karyawanWni,
                "karyawanWna" => $karyawanWna,
            ];
            return response()->json(['message' => 'success', 'data' => $dataDashboard]);
        } else if ($dataFnb) {
            $dataKaryawan = KaryawanFnb::where('fnb_id', $dataFnb->id)->get();
            $karyawanIds = $dataKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $dataKaryawanPria = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $dataKaryawanWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();
            $karyawanSd = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SD/MI')->count();
            $karyawanSmp = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMP/MTS')->count();
            $karyawanSma = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'SMA/SMK/MA')->count();
            $karyawanS1 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'D3/S1/D4')->count();
            $karyawanS2 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S2')->count();
            $karyawanS3 = Karyawan::whereIn('id', $karyawanIds)->where('pendidikanKaryawan', 'S3')->count();
            $karyawanWni = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNI')->count();
            $karyawanWna = Karyawan::whereIn('id', $karyawanIds)->where('wargaNegara', 'WNA')->count();
            $dataDashboard = [
                "jumlahKaryawanPria" => $dataKaryawanPria,
                "jumlahKaryawanWanita" => $dataKaryawanWanita,
                "karyawanSd" => $karyawanSd,
                "karyawanSmp" => $karyawanSmp,
                "karyawanSma" => $karyawanSma,
                "karyawanS1" => $karyawanS1,
                "karyawanS2" => $karyawanS2,
                "karyawanS3" => $karyawanS3,
                "karyawanWni" => $karyawanWni,
                "karyawanWna" => $karyawanWna,
            ];
            return response()->json(['message' => 'success', 'data' => $dataDashboard]);
        } else {
            return response()->json(['message' => 'gagal']);
        }
    }
}
