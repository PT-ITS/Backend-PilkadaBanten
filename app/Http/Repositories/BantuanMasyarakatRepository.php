<?php

namespace App\Http\Repositories;

use App\Models\bantuan_masyarakat;
use Illuminate\Support\Facades\DB;

class BantuanMasyarakatRepository
{
    private $bantuanMasyarakatModel;

    public function __construct(bantuan_masyarakat $bantuanMasyarakatModel)
    {
        $this->bantuanMasyarakatModel = $bantuanMasyarakatModel;
    }

    public function listDataBantuanMasyarakat()
    {
        try {
            $dataBantuanMasyarakat = $this->bantuanMasyarakatModel->get();
            return [
                "statusCode" => 200,
                "data" => $dataBantuanMasyarakat,
                "message" => 'get data bantuan masyarakat success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataBantuanMasyarakat($id)
    {
        try {
            $dataBantuanMasyarakat = $this->bantuanMasyarakatModel->find($id);

            if (!$dataBantuanMasyarakat) {
                throw new \Exception('bantuan masyarakat data not found');
            }

            return [
                "statusCode" => 200,
                "data" => $dataBantuanMasyarakat,
                "message" => 'get detail data bantuan masyarakat success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function inputDataBantuanMasyarakat($dataRequest)
    // {
    //     try {
    //         $result = $this->bantuanMasyarakatModel->insert($dataRequest);
    //         return [
    //             "statusCode" => 201,
    //             "message" => 'input data bantuanMasyarakat success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataBantuanMasyarakat($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataBantuanMasyarakat = $this->bantuanMasyarakatModel->find($id);
            $dataBantuanMasyarakat->pelaksana = $dataRequest['pelaksana'];
            $dataBantuanMasyarakat->tanggal = $dataRequest['tanggal'];
            $dataBantuanMasyarakat->lokasi = $dataRequest['lokasi'];
            $dataBantuanMasyarakat->jenis_barang = $dataRequest['jenis_barang'];
            $dataBantuanMasyarakat->jumlah_yang_disalurkan = $dataRequest['jumlah_yang_disalurkan'];
            $dataBantuanMasyarakat->sasaran_penerima = $dataRequest['sasaran_penerima'];
            $dataBantuanMasyarakat->penanggung_jawab = $dataRequest['penanggung_jawab'];
            $dataBantuanMasyarakat->save();

            DB::commit();
            return [
                "statusCode" => 200,
                "message" => 'update data bantuan masyarakat success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataBantuanMasyarakat($id)
    {
        try {
            $bantuanMasyarakat = $this->bantuanMasyarakatModel->find($id);
            if ($bantuanMasyarakat) {
                // Delete the bantuan masyarakat
                $bantuanMasyarakat->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data bantuan masyarakat success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data bantuan masyarakat tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
