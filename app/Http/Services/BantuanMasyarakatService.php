<?php

namespace App\Http\Services;

use App\Http\Repositories\BantuanMasyarakatRepository;

class BantuanMasyarakatService
{
    private $bantuanMasyarakatRepository;

    public function __construct(BantuanMasyarakatRepository $bantuanMasyarakatRepository)
    {
        $this->bantuanMasyarakatRepository = $bantuanMasyarakatRepository;
    }

    public function listDataBantuanMasyarakat()
    {
        try {
            return $this->bantuanMasyarakatRepository->listDataBantuanMasyarakat();
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
            return $this->bantuanMasyarakatRepository->detailDataBantuanMasyarakat($id);
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
    //         return $this->bantuanMasyarakatRepository->inputDataBantuanMasyarakat($dataRequest);
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataBantuanMasyarakat($dataRequest, $id)
    {
        try {
            return $this->bantuanMasyarakatRepository->updateDataBantuanMasyarakat($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataBantuanMasyarakat($id)
    {
        try {
            return $this->bantuanMasyarakatRepository->deleteDataBantuanMasyarakat($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
