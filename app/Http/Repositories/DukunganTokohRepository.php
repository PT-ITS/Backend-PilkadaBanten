<?php

namespace App\Http\Repositories;

use App\Models\dukungan_tokoh;
use Illuminate\Support\Facades\DB;

class DukunganTokohRepository
{
    private $dukunganTokohModel;

    public function __construct(dukungan_tokoh $dukunganTokohModel)
    {
        $this->dukunganTokohModel = $dukunganTokohModel;
    }

    public function listDataDukunganTokoh()
    {
        try {
            $dataDukunganTokoh = $this->dukunganTokohModel
                ->join('jenis_dukungans', 'dukungan_tokohs.id', '=', 'jenis_dukungans.dukungan_tokoh_id')
                ->select('dukungan_tokohs.*', 'jenis_dukungans.jenis_dukungan', 'jenis_dukungans.jumlah')
                ->get();
            return [
                "statusCode" => 200,
                "data" => $dataDukunganTokoh,
                "message" => 'get data dukungan tokoh success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataDukunganTokoh($id)
    {
        try {
            $dataDukunganTokoh = $this->dukunganTokohModel
                ->join('jenis_dukungans', 'dukungan_tokohs.id', '=', 'jenis_dukungans.dukungan_tokoh_id')
                ->select('dukungan_tokohs.*', 'jenis_dukungans.jenis_dukungan', 'jenis_dukungans.jumlah')
                ->where('dukungan_tokohs.id', $id)
                ->get();

            if (!$dataDukunganTokoh) {
                throw new \Exception('dukungan tokoh data not found');
            }

            return [
                "statusCode" => 200,
                "data" => $dataDukunganTokoh,
                "message" => 'get detail data dukungan tokoh success'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function inputDataDukunganTokoh($dataRequest)
    // {
    //     try {
    //         $result = $this->dukunganTokohModel->insert($dataRequest);
    //         return [
    //             "statusCode" => 201,
    //             "message" => 'input data dukunganTokoh success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataDukunganTokoh($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataDukunganTokoh = $this->dukunganTokohModel->find($id);
            $dataDukunganTokoh->pelaksana = $dataRequest['pelaksana'];
            $dataDukunganTokoh->tanggal = $dataRequest['tanggal'];
            $dataDukunganTokoh->lokasi = $dataRequest['lokasi'];
            $dataDukunganTokoh->sasaran = $dataRequest['sasaran'];
            $dataDukunganTokoh->penanggung_jawab = $dataRequest['penanggung_jawab'];
            $dataDukunganTokoh->save();

            DB::commit();
            return [
                "statusCode" => 200,
                "message" => 'update data dukungan tokoh success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataDukunganTokoh($id)
    {
        try {
            $dukunganTokoh = $this->dukunganTokohModel->find($id);
            if ($dukunganTokoh) {
                // Delete the dukungan tokoh
                $dukunganTokoh->delete();
                return [
                    "statusCode" => 200,
                    "message" => 'delete data dukungan tokoh success'
                ];
            }
            return [
                "statusCode" => 404,
                "message" => 'data dukungan tokoh tidak ditemukan'
            ];
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
