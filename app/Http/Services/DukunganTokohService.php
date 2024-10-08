<?php

namespace App\Http\Services;

use App\Http\Repositories\DukunganTokohRepository;

class DukunganTokohService
{
    private $dukunganTokohRepository;

    public function __construct(DukunganTokohRepository $dukunganTokohRepository)
    {
        $this->dukunganTokohRepository = $dukunganTokohRepository;
    }

    public function listDataDukunganTokoh()
    {
        try {
            return $this->dukunganTokohRepository->listDataDukunganTokoh();
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
            return $this->dukunganTokohRepository->detailDataDukunganTokoh($id);
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
    //         return $this->dukunganTokohRepository->inputDataDukunganTokoh($dataRequest);
    //     } catch (\Exception $e) {
    //         return [
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function updateDataDukunganTokoh($dataRequest, $id)
    {
        try {
            return $this->dukunganTokohRepository->updateDataDukunganTokoh($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataDukunganTokoh($id)
    {
        try {
            return $this->dukunganTokohRepository->deleteDataDukunganTokoh($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
