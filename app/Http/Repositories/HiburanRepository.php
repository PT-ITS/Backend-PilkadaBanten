<?php

namespace App\Http\Repositories;

use App\Models\Hiburan;
use App\Models\Karyawan;
use App\Models\KaryawanHiburan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HiburanRepository
{
    private $hiburanModel;

    public function __construct(Hiburan $hiburanModel)
    {
        $this->hiburanModel = $hiburanModel;
    }

    public function listHiburan()
    {
        try {
            $dataHiburan = $this->hiburanModel->get();
            return [
                "statusCode" => 200,
                "data" => $dataHiburan,
                "message" => 'get data hiburan success'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailHiburan($id)
    {
        try {
            $dataHiburan = $this->hiburanModel->find($id);

            if (!$dataHiburan) {
                throw new \Exception('hiburan data not found');
            }

            $dataKaryawan = Karyawan::join('karyawan_hiburans', 'karyawans.id', '=', 'karyawan_hiburans.karyawan_id')
                ->select('karyawans.*', 'karyawan_hiburans.karyawan_id', 'karyawan_hiburans.hiburan_id')
                ->where('karyawan_hiburans.hiburan_id', $id)
                ->get();

            // ambil karyawan hiburans yang id nya sama dengan id hiburan
            $jumlahKaryawan = KaryawanHiburan::where('hiburan_id', $id)->get();

            // Mengambil ID karyawan dari hasil query di atas
            $karyawanIds = $jumlahKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $jumlahLaki = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $jumlahWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();

            return [
                "statusCode" => 200,
                "data" => [
                    "hiburan" => $dataHiburan,
                    "karyawan" => $dataKaryawan,
                    "lakiLaki" => $jumlahLaki,
                    "perempuan" => $jumlahWanita
                ],
                "message" => 'get detail data hiburan and karyawan hiburan success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }


    public function createHiburan($requestData)
    {
        try {

            $this->hiburanModel->create($requestData);
            return [
                "statusCode" => 201,
                "message" => 'input data hiburan success'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateHiburan($requestData, $id)
    {
        DB::beginTransaction();
        try {
            $hiburan = $this->hiburanModel->find($id);
            if ($hiburan) {
                $hiburan->update($requestData);

                $dataUser = User::find($hiburan->pj_id);
                $dataUser->email = $requestData['emailPj'];
                $dataUser->password = bcrypt($requestData['passwordPj']);
                $dataUser->save();

                DB::commit();
                return [
                    "statusCode" => 200,
                    "message" => 'update data hiburan success'
                ];
            }

            DB::rollBack();
            return [
                "statusCode" => 404,
                "message" => 'data hiburan tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateHiburan($id)
    {
        DB::beginTransaction();
        try {
            $hiburan = $this->hiburanModel->find($id);
            if ($hiburan) {
                $hiburan->status = '1';
                $hiburan->save();

                DB::commit();
                return [
                    "statusCode" => 200,
                    "message" => 'validate data hiburan success'
                ];
            }

            DB::rollBack();
            return [
                "statusCode" => 404,
                "message" => 'data hiburan tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteHiburan($id)
    {
        try {
            $hiburan = $this->hiburanModel->find($id);
            if ($hiburan) {
                $relatedKaryawanIds = DB::table('karyawan_hiburans')
                    ->where('hiburan_id', $id)
                    ->pluck('karyawan_id')
                    ->toArray();

                // Delete related karyawan entries
                DB::table('karyawans')->whereIn('id', $relatedKaryawanIds)->delete();

                // Delete user
                User::where('id', $hiburan->pj_id)->delete();

                $hiburan->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data hiburan success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data hiburan tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
