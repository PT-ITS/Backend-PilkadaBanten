<?php

namespace App\Http\Repositories;

use App\Models\Hotel;
use App\Models\Karyawan;
use App\Models\KaryawanHotel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HotelRepository
{
    private $hotelModel;

    public function __construct(Hotel $hotelModel)
    {
        $this->hotelModel = $hotelModel;
    }

    public function listDataHotel()
    {
        try {
            $dataHotel = $this->hotelModel->get();
            return [
                "statusCode" => 200,
                "data" => $dataHotel,
                "message" => 'get data hotel success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataHotel($id)
    {
        try {
            $dataHotel = $this->hotelModel->find($id);

            if (!$dataHotel) {
                throw new \Exception('hotel data not found');
            }

            $dataKaryawan = Karyawan::join('karyawan_hotels', 'karyawans.id', '=', 'karyawan_hotels.karyawan_id')
                ->select('karyawans.*', 'karyawan_hotels.karyawan_id', 'karyawan_hotels.hotel_id')
                ->where('karyawan_hotels.hotel_id', $id)
                ->get();

            // ambil karyawan hotels yang id nya sama dengan id hotel
            $jumlahKaryawan = KaryawanHotel::where('hotel_id', $id)->get();

            // Mengambil ID karyawan dari hasil query di atas
            $karyawanIds = $jumlahKaryawan->pluck('karyawan_id');

            // Menghitung jumlah karyawan laki-laki dan perempuan
            $jumlahLaki = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '1')->count();
            $jumlahWanita = Karyawan::whereIn('id', $karyawanIds)->where('jenisKelamin', '0')->count();


            return [
                "statusCode" => 200,
                "data" => [
                    "hotel" => $dataHotel,
                    "karyawan" => $dataKaryawan,
                    "lakiLaki" => $jumlahLaki,
                    "perempuan" => $jumlahWanita
                ],
                "message" => 'get detail data hotel and karyawan hotel success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataHotel($dataRequest)
    {
        try {
            $result = $this->hotelModel->insert($dataRequest);
            return [
                "statusCode" => 201,
                "message" => 'input data hotel success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataHotel($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataHotel = $this->hotelModel->find($id);
            $dataHotel->nib = $dataRequest['nib'];
            $dataHotel->namaHotel = $dataRequest['namaHotel'];
            $dataHotel->resiko = $dataRequest['resiko'];
            $dataHotel->skalaUsaha = $dataRequest['skalaUsaha'];
            $dataHotel->bintangHotel = $dataRequest['bintangHotel'];
            $dataHotel->kamarVip = $dataRequest['kamarVip'];
            $dataHotel->kamarStandart = $dataRequest['kamarStandart'];
            $dataHotel->alamat = $dataRequest['alamat'];
            $dataHotel->koordinat = $dataRequest['koordinat'];
            $dataHotel->namaPj = $dataRequest['namaPj'];
            $dataHotel->emailPj = $dataRequest['emailPj'];
            $dataHotel->passwordPj = $dataRequest['passwordPj'];
            $dataHotel->nikPj = $dataRequest['nikPj'];
            $dataHotel->pendidikanPj = $dataRequest['pendidikanPj'];
            $dataHotel->teleponPj = $dataRequest['teleponPj'];
            $dataHotel->wargaNegaraPj = $dataRequest['wargaNegaraPj'];
            $dataHotel->surveyor_id = $dataRequest['surveyor_id'];
            $dataHotel->save();

            $dataUser = User::find($dataHotel->pj_id);
            $dataUser->email = $dataRequest['emailPj'];
            $dataUser->password = bcrypt($dataRequest['passwordPj']);
            $dataUser->save();

            DB::commit();
            return [
                "statusCode" => 200,
                "message" => 'update data hotel success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateDataHotel($id)
    {
        DB::beginTransaction();
        try {
            $dataHotel = $this->hotelModel->find($id);
            if ($dataHotel) {
                $dataHotel->status = '1';
                $dataHotel->save();

                DB::commit();
                return [
                    "statusCode" => 200,
                    "message" => 'validate data hotel success'
                ];
            }

            DB::rollBack();
            return [
                "statusCode" => 404,
                "message" => 'data hotel tidak ditemukan'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataHotel($id)
    {
        try {
            $hotel = $this->hotelModel->find($id);
            if ($hotel) {
                $relatedKaryawanIds = DB::table('karyawan_hotels')
                    ->where('hotel_id', $id)
                    ->pluck('karyawan_id')
                    ->toArray();

                // Delete related karyawan entries
                DB::table('karyawans')->whereIn('id', $relatedKaryawanIds)->delete();

                // Delete user
                User::where('id', $hotel->pj_id)->delete();

                // Delete the hotel
                $hotel->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data hotel success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data hotel tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
