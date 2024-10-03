<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\KaryawanService;
use App\Imports\KaryawanImport;
use App\Models\Karyawan;
use App\Models\KaryawanFnb;
use App\Models\KaryawanHiburan;
use App\Models\KaryawanHotel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    private $karyawanService;

    public function __construct(KaryawanService $karyawanService)
    {
        $this->karyawanService = $karyawanService;
    }

    public function importKaryawanHotel(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new KaryawanImport, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                // Lakukan validasi atau manipulasi data sesuai kebutuhan
                $karyawan = new Karyawan([
                    'namaKaryawan'  => $data['namakaryawan'],
                    'jabatanKaryawan' => $data['jabatankaryawan'],
                    'alamatKaryawan' => $data['alamatkaryawan'],
                    'jenisKelamin' => $data['jeniskelamin'] == 'Laki-laki' ? '1' : '0',
                    'wargaNegara' => $data['warganegara'],
                    'sertifikasiKaryawan' => $data['sertifikasikaryawan'] == 'Yes' ? '1' : '0',
                    'pendidikanKaryawan' => $data['pendidikankaryawan'],
                    'surveyor_id' => auth()->user()->id,
                ]);
                $karyawan->save();

                $karyawanHotel = new KaryawanHotel([
                    'karyawan_id' => $karyawan->id,
                    'hotel_id' => $id
                ]);

                // Coba simpan user ke database
                if ($karyawanHotel->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function importKaryawanHiburan(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new KaryawanImport, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                // Lakukan validasi atau manipulasi data sesuai kebutuhan
                $karyawan = new Karyawan([
                    'namaKaryawan'  => $data['namakaryawan'],
                    'jabatanKaryawan' => $data['jabatankaryawan'],
                    'alamatKaryawan' => $data['alamatkaryawan'],
                    'jenisKelamin' => $data['jeniskelamin'] == 'Laki-laki' ? '1' : '0',
                    'wargaNegara' => $data['warganegara'],
                    'sertifikasiKaryawan' => $data['sertifikasikaryawan'] == 'Yes' ? '1' : '0',
                    'pendidikanKaryawan' => $data['pendidikankaryawan'],
                    'surveyor_id' => auth()->user()->id,
                ]);
                $karyawan->save();

                $karyawanHiburan = new KaryawanHiburan([
                    'karyawan_id' => $karyawan->id,
                    'hiburan_id' => $id
                ]);

                // Coba simpan user ke database
                if ($karyawanHiburan->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function importKaryawanFnb(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new KaryawanImport, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                // Lakukan validasi atau manipulasi data sesuai kebutuhan
                $karyawan = new Karyawan([
                    'namaKaryawan'  => $data['namakaryawan'],
                    'jabatanKaryawan' => $data['jabatankaryawan'],
                    'alamatKaryawan' => $data['alamatkaryawan'],
                    'jenisKelamin' => $data['jeniskelamin'] == 'Laki-laki' ? '1' : '0',
                    'wargaNegara' => $data['warganegara'],
                    'sertifikasiKaryawan' => $data['sertifikasikaryawan'] == 'Yes' ? '1' : '0',
                    'pendidikanKaryawan' => $data['pendidikankaryawan'],
                    'surveyor_id' => auth()->user()->id,
                ]);
                $karyawan->save();

                $karyawanFnb = new KaryawanFnb([
                    'karyawan_id' => $karyawan->id,
                    'fnb_id' => $id
                ]);

                // Coba simpan user ke database
                if ($karyawanFnb->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function listKaryawanHotel($id)
    {
        $result = $this->karyawanService->listKaryawanHotel($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function listKaryawanHiburan($id)
    {
        $result = $this->karyawanService->listKaryawanHiburan($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function listKaryawanFnb($id)
    {
        $result = $this->karyawanService->listKaryawanFnb($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function inputDataKaryawanHotel(Request $request)
    {
        $validateData = $request->validate([
            'namaKaryawan' => 'required',
            // 'nikKaryawan' => 'required',
            'pendidikanKaryawan' => 'required',
            'jabatanKaryawan' => 'required',
            'alamatKaryawan' => 'required',
            'sertifikasiKaryawan' => 'required',
            'wargaNegara' => 'required',
            'jenisKelamin' => 'required',
            'hotel_id' => 'required',
        ]);

        $result = $this->karyawanService->inputDataKaryawanHotel($validateData);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function inputDataKaryawanHiburan(Request $request)
    {
        $validateData = $request->validate([
            'namaKaryawan' => 'required',
            // 'nikKaryawan' => 'required',
            'pendidikanKaryawan' => 'required',
            'jabatanKaryawan' => 'required',
            'alamatKaryawan' => 'required',
            'sertifikasiKaryawan' => 'required',
            'wargaNegara' => 'required',
            'jenisKelamin' => 'required',
            'hiburan_id' => 'required',
        ]);

        $result = $this->karyawanService->inputDataKaryawanHiburan($validateData);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function inputDataKaryawanFnb(Request $request)
    {
        $validateData = $request->validate([
            'namaKaryawan' => 'required',
            // 'nikKaryawan' => 'required',
            'pendidikanKaryawan' => 'required',
            'jabatanKaryawan' => 'required',
            'alamatKaryawan' => 'required',
            'sertifikasiKaryawan' => 'required',
            'wargaNegara' => 'required',
            'jenisKelamin' => 'required',
            'fnb_id' => 'required',
        ]);

        $result = $this->karyawanService->inputDataKaryawanFnb($validateData);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function updateDataKaryawan(Request $request, $id)
    {
        $validateData = $request->validate([
            'namaKaryawan' => 'required',
            // 'nikKaryawan' => 'required',
            'pendidikanKaryawan' => 'required',
            'jabatanKaryawan' => 'required',
            'alamatKaryawan' => 'required',
            'sertifikasiKaryawan' => 'required',
            'wargaNegara' => 'required',
            'surveyor_id' => 'required',
            'jenisKelamin' => 'required',
        ]);

        $result = $this->karyawanService->updateDataKaryawan($validateData, $id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function deleteDataKaryawan($id)
    {
        $result = $this->karyawanService->deleteDataKaryawan($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }
}
