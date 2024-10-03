<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\HiburanService;
use App\Models\User;
use App\Models\Hiburan;
use App\Models\Karyawan;
use App\Models\KaryawanHiburan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HiburanController extends Controller
{
    private $hiburanService;

    public function __construct(HiburanService $hiburanService)
    {
        $this->hiburanService = $hiburanService;
    }

    public function listHiburan()
    {
        $result = $this->hiburanService->listHiburan();

        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function detailHiburan($id)
    {
        $result = $this->hiburanService->detailHiburan($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function inputHiburanAndKaryawan(Request $request)
    {
        try {
            // Validasi data hiburan
            $validateHiburanData = $request->validate([
                'hiburan.nib' => 'required',
                'hiburan.namaHiburan' => 'required',
                'hiburan.resiko' => 'required',
                'hiburan.skalaUsaha' => 'required',
                'hiburan.alamat' => 'required',
                'hiburan.koordinat' => 'required',
                'hiburan.namaPj' => 'required',
                'hiburan.emailPj' => 'required|email',
                'hiburan.passwordPj' => 'required',
                'hiburan.nikPj' => 'required',
                'hiburan.pendidikanPj' => 'required',
                'hiburan.teleponPj' => 'required',
                'hiburan.wargaNegaraPj' => 'required',
            ]);

            // Validasi data karyawan
            $validateKaryawanData = $request->validate([
                'karyawan.*.namaKaryawan' => 'required',
                // 'karyawan.*.nikKaryawan' => 'required',
                'karyawan.*.pendidikanKaryawan' => 'required',
                'karyawan.*.jabatanKaryawan' => 'required',
                'karyawan.*.alamatKaryawan' => 'required',
                'karyawan.*.wargaNegara' => 'required',
                'karyawan.*.jenisKelamin' => 'required',
            ]);

            DB::beginTransaction();

            try {
                // Simpan data user PJ
                $user = new User();
                $user->name = $validateHiburanData['hiburan']['namaPj'];
                $user->email = $validateHiburanData['hiburan']['emailPj'];
                $user->password = bcrypt($validateHiburanData['hiburan']['passwordPj']);
                $user->alamat = $validateHiburanData['hiburan']['alamat'];
                $user->noHP = $validateHiburanData['hiburan']['teleponPj'];
                $user->level = '2';
                $user->status = '1';
                $user->save();
                // Simpan data hiburan
                $hiburan = new Hiburan();
                $hiburan->fill($validateHiburanData['hiburan']);
                $hiburan->surveyor_id = auth()->user()->id;
                $hiburan->pj_id = $user->id;
                $hiburan->save();

                // Simpan data karyawan
                foreach ($validateKaryawanData['karyawan'] as $karyawanData) {
                    $karyawan = new Karyawan();
                    $karyawan->fill($karyawanData);
                    $karyawan->surveyor_id = auth()->user()->id;
                    $karyawan->save();

                    $karyawanHiburan = new KaryawanHiburan();
                    $karyawanHiburan->karyawan_id = $karyawan->id;
                    $karyawanHiburan->hiburan_id = $hiburan->id;
                    $karyawanHiburan->save();
                }

                DB::commit();
                return response()->json(['message' => 'Data hiburan dan karyawan berhasil disimpan'], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    // public function createHiburan(Request $request)
    // {
    //     $validateData = $request->validate([
    //         'namaHiburan' => 'required',
    //         'alamat' => 'required',
    //         'koordinat' => 'required',
    //         'namaPj' => 'required',
    //         'nikPj' => 'required',
    //         'pendidikanPj' => 'required',
    //         'teleponPj' => 'required',
    //         'wargaNegaraPj' => 'required',
    //         'surveyor_id' => 'required',
    //     ]);

    //     $result = $this->hiburanService->createHiburan($validateData);
    //     return response()->json(
    //         [
    //             'message' => $result['message']
    //         ],
    //         $result['statusCode']
    //     );
    // }

    public function updateHiburan(Request $request, $id)
    {
        $validateData = $request->validate([
            'nib' => 'required',
            'namaHiburan' => 'required',
            'resiko' => 'required',
            'skalaUsaha' => 'required',
            'alamat' => 'required',
            'koordinat' => 'required',
            'namaPj' => 'required',
            'emailPj' => 'required',
            'passwordPj' => 'required',
            'nikPj' => 'required',
            'pendidikanPj' => 'required',
            'teleponPj' => 'required',
            'wargaNegaraPj' => 'required',
            'surveyor_id' => 'required',
        ]);
        $validateData['surveyor_id'] = auth()->user()->id;

        $result = $this->hiburanService->updateHiburan($validateData, $id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function validateHiburan($id)
    {
        $result = $this->hiburanService->validateHiburan($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function deleteHiburan($id)
    {
        $result = $this->hiburanService->deleteHiburan($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }
}
