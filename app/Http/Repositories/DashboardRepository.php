<?php

namespace App\Http\Repositories;

use App\Models\Karyawan;
use App\Models\Hotel;
use App\Models\Hiburan;
use App\Models\Fnb;

class DashboardRepository
{
    public function getDataDashboard()
    {
        try {
            // Menghitung jumlah karyawan pria dan wanita
            $dataKaryawanPria = Karyawan::where('jenisKelamin', '1')->count();
            $dataKaryawanWanita = Karyawan::where('jenisKelamin', '0')->count();
            $karyawanSd = Karyawan::where('pendidikanKaryawan', 'SD/MI')->count();
            $karyawanSmp = Karyawan::where('pendidikanKaryawan', 'SMP/MTS')->count();
            $karyawanSma = Karyawan::where('pendidikanKaryawan', 'SMA/SMK/MA')->count();
            $karyawanS1 = Karyawan::where('pendidikanKaryawan', 'D3/S1/D4')->count();
            $karyawanS2 = Karyawan::where('pendidikanKaryawan', 'S2')->count();
            $karyawanS3 = Karyawan::where('pendidikanKaryawan', 'S3')->count();
            $karyawanWni = Karyawan::where('wargaNegara', 'WNI')->count();
            $karyawanWna = Karyawan::where('wargaNegara', 'WNA')->count();

            // Menghitung jumlah data hotel, hiburan, dan F&B
            $dataHotel = Hotel::count();
            $dataHiburan = Hiburan::count();
            $dataFnb = Fnb::count();

            // Membuat array data dashboard
            $dataDashboard = [
                "jumlahKaryawanPria" => $dataKaryawanPria,
                "jumlahKaryawanWanita" => $dataKaryawanWanita,
                "jumlahHotel" => $dataHotel,
                "jumlahHiburan" => $dataHiburan,
                "jumlahFnb" => $dataFnb,
                "karyawanSd" => $karyawanSd,
                "karyawanSmp" => $karyawanSmp,
                "karyawanSma" => $karyawanSma,
                "karyawanS1" => $karyawanS1,
                "karyawanS2" => $karyawanS2,
                "karyawanS3" => $karyawanS3,
                "karyawanWni" => $karyawanWni,
                "karyawanWna" => $karyawanWna,
            ];

            // Mengembalikan response sukses
            return [
                "statusCode" => 200,
                "data" => $dataDashboard,
                "message" => 'Get data dashboard success'
            ];
        } catch (\Exception $e) {
            // Mengembalikan response error
            return [
                "statusCode" => 500, // Menggunakan 500 untuk server error
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function exportAll()
    {
        try {
            $hotelData = Hotel::get();
            $hiburanData = Hiburan::get();
            $fnbData = Fnb::get();

            $allData = [
                "hotel" => $hotelData,
                "hiburan" => $hiburanData,
                "fnb" => $fnbData
            ];

            return [
                "statusCode" => 200,
                "data" => $allData,
                "message" => 'get semua data success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
    public function listAll()
    {
        try {
            // Mendapatkan data hotel dengan kolom yang diinginkan
            $hotelData = Hotel::select([
                'id',
                'nib',
                'namaHotel',
                'alamat',
                'namaPj',
                'teleponPj',
                'status',
            ])->get();

            // Mendapatkan data hiburan dengan kolom yang diinginkan
            $hiburanData = Hiburan::select([
                'id',
                'nib',
                'namaHiburan',
                'alamat',
                'namaPj',
                'teleponPj',
                'status',
            ])->get();

            // Mendapatkan data fnb dengan kolom yang diinginkan
            $fnbData = Fnb::select([
                'id',
                'nib',
                'namaFnb',
                'alamat',
                'namaPj',
                'teleponPj',
                'status',
            ])->get();

            $allData = [
                "hotel" => $hotelData,
                "hiburan" => $hiburanData,
                "fnb" => $fnbData
            ];

            return [
                "statusCode" => 200,
                "data" => $allData,
                "message" => 'Get semua data success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }


    public function listAllBySurveyor()
    {
        try {
            $hotelData = Hotel::where('surveyor_id', auth()->user()->id)->get();
            $hiburanData = Hiburan::where('surveyor_id', auth()->user()->id)->get();
            $fnbData = Fnb::where('surveyor_id', auth()->user()->id)->get();

            $allData = [
                "hotel" => $hotelData,
                "hiburan" => $hiburanData,
                "fnb" => $fnbData
            ];

            return [
                "statusCode" => 200,
                "data" => $allData,
                "message" => 'get data berdasarkan surveyor success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listAllByPengelola()
    {
        try {
            $hotelData = Hotel::where('pj_id', auth()->user()->id)->get();
            $hiburanData = Hiburan::where('pj_id', auth()->user()->id)->get();
            $fnbData = Fnb::where('pj_id', auth()->user()->id)->get();

            $allData = [
                "hotel" => $hotelData,
                "hiburan" => $hiburanData,
                "fnb" => $fnbData
            ];

            return [
                "statusCode" => 200,
                "data" => $allData,
                "message" => 'get data berdasarkan surveyor success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
}
