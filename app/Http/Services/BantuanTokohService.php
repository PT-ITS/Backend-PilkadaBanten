<?php

namespace App\Http\Services;

use App\Http\Repositories\BantuanTokohRepository;

class BantuanTokohService
{
    private $bantuanTokohRepository;

    public function __construct(BantuanTokohRepository $bantuanTokohRepository)
    {
        $this->bantuanTokohRepository = $bantuanTokohRepository;
    }

    public function listDataBantuanTokoh()
    {
        try {
            return $this->bantuanTokohRepository->listDataBantuanTokoh();
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
            return $this->bantuanTokohRepository->detailDataBantuanTokoh($id);
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
    //         return $this->bantuanTokohRepository->inputDataBantuanTokoh($dataRequest);
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataBantuanTokoh($dataRequest, $id)
    {
        try {
            return $this->bantuanTokohRepository->updateDataBantuanTokoh($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataBantuanTokoh($id)
    {
        try {
            return $this->bantuanTokohRepository->deleteDataBantuanTokoh($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
