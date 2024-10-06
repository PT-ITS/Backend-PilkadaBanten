<?php

namespace App\Http\Repositories;

use App\Models\bantuan_tokoh;
use Illuminate\Support\Facades\DB;

class BantuanTokohRepository
{
    private $bantuanTokohModel;

    public function __construct(bantuan_tokoh $bantuanTokohModel)
    {
        $this->bantuanTokohModel = $bantuanTokohModel;
    }

    public function listDataBantuanTokoh()
    {
        try {
            $dataBantuanTokoh = $this->bantuanTokohModel
                ->join('jenis_barangs', 'bantuan_tokohs.id', '=', 'jenis_barangs.bantuan_tokoh_id')
                ->select('bantuan_tokohs.*', 'jenis_barangs.jenis_barang', 'jenis_barangs.jumlah')
                ->get();
            return [
                "statusCode" => 200,
                "data" => $dataBantuanTokoh,
                "message" => 'get data bantuan tokoh success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataBantuanTokoh($id)
    {
        try {
            $dataBantuanTokoh = $this->bantuanTokohModel
                ->join('jenis_barangs', 'bantuan_tokohs.id', '=', 'jenis_barangs.bantuan_tokoh_id')
                ->select('bantuan_tokohs.*', 'jenis_barangs.jenis_barang', 'jenis_barangs.jumlah')
                ->where('bantuan_tokohs.id', $id)
                ->get();

            if (!$dataBantuanTokoh) {
                throw new \Exception('bantuan tokoh data not found');
            }

            return [
                "statusCode" => 200,
                "data" => $dataBantuanTokoh,
                "message" => 'get detail data bantuan tokoh success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function inputDataBantuanTokoh($dataRequest)
    // {
    //     try {
    //         $result = $this->bantuanTokohModel->insert($dataRequest);
    //         return [
    //             "statusCode" => 201,
    //             "message" => 'input data bantuanTokoh success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataBantuanTokoh($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataBantuanTokoh = $this->bantuanTokohModel->find($id);
            $dataBantuanTokoh->pelaksana = $dataRequest['pelaksana'];
            $dataBantuanTokoh->tanggal = $dataRequest['tanggal'];
            $dataBantuanTokoh->lokasi = $dataRequest['lokasi'];
            $dataBantuanTokoh->sasaran = $dataRequest['sasaran'];
            $dataBantuanTokoh->penanggung_jawab = $dataRequest['penanggung_jawab'];
            $dataBantuanTokoh->save();

            DB::commit();
            return [
                "statusCode" => 200,
                "message" => 'update data bantuan tokoh success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataBantuanTokoh($id)
    {
        try {
            $bantuanTokoh = $this->bantuanTokohModel->find($id);
            if ($bantuanTokoh) {
                // Delete the bantuan tokoh
                $bantuanTokoh->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data bantuan tokoh success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data bantuan tokoh tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
