<?php

namespace App\Http\Repositories;

use App\Models\Fnb;
use App\Models\Karyawan;
use App\Models\KaryawanFnb;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FnbRepository
{
    private $fnbModel;

    public function __construct(Fnb $fnbModel)
    {
        $this->fnbModel = $fnbModel;
    }

    public function listFnb()
    {
        try {
            $dataFnb = $this->fnbModel->get();
            return [
                "statusCode" => 200,
                "data" => $dataFnb,
                "message" => 'get data fnb success'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailFnb($id)
    {
        try {
            $dataFnb = $this->fnbModel->find($id);

            if (!$dataFnb) {
                throw new \Exception('fnb data not found');
            }

            $dataKaryawan = Karyawan::join('karyawan_fnbs', 'karyawans.id', '=', 'karyawan_fnbs.karyawan_id')
                ->select('karyawans.*', 'karyawan_fnbs.karyawan_id', 'karyawan_fnbs.fnb_id')
                ->where('karyawan_fnbs.fnb_id', $id)
                ->get();

            // ambil karyawan fnbs yang id nya sama dengan id fnb
            $jumlahKaryawan = KaryawanFnb::where('fnb_id', $id)->get();

            // Mengambil ID karyawan dari hasil query di atas
            $karyawanIds = $jumlahKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $jumlahLaki = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $jumlahWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();

            return [
                "statusCode" => 200,
                "data" => [
                    "fnb" => $dataFnb,
                    "karyawan" => $dataKaryawan,
                    "lakiLaki" => $jumlahLaki,
                    "perempuan" => $jumlahWanita
                ],
                "message" => 'get detail data fnb and karyawan fnb success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function createFnb($requestData)
    {
        try {

            $this->fnbModel->create($requestData);
            return [
                "statusCode" => 201,
                "message" => 'input data fnb success'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateFnb($requestData, $id)
    {
        DB::beginTransaction();
        try {
            $fnb = $this->fnbModel->find($id);
            if ($fnb) {
                $fnb->update($requestData);

                $dataUser = User::find($fnb->pj_id);
                $dataUser->email = $requestData['emailPj'];
                $dataUser->password = bcrypt($requestData['passwordPj']);
                $dataUser->save();

                DB::commit();
                return [
                    "statusCode" => 200,
                    "message" => 'update data fnb success'
                ];
            }

            DB::rollBack();
            return [
                "statusCode" => 404,
                "message" => 'data fnb tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateFnb($id)
    {
        DB::beginTransaction();
        try {
            $fnb = $this->fnbModel->find($id);
            if ($fnb) {
                $fnb->status = '1';
                $fnb->save();

                DB::commit();
                return [
                    "statusCode" => 200,
                    "message" => 'validate data fnb success'
                ];
            }

            DB::rollBack();
            return [
                "statusCode" => 404,
                "message" => 'data fnb tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteFnb($id)
    {
        try {
            $fnb = $this->fnbModel->find($id);
            if ($fnb) {
                $relatedKaryawanIds = DB::table('karyawan_fnbs')
                    ->where('fnb_id', $id)
                    ->pluck('karyawan_id')
                    ->toArray();

                // Delete related karyawan entries
                DB::table('karyawans')->whereIn('id', $relatedKaryawanIds)->delete();

                // Delete user
                User::where('id', $fnb->pj_id)->delete();

                $fnb->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data fnb success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data fnb tidak ditemukan'
            ];
        } catch (\Exception  $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
