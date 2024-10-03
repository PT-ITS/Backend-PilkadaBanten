<?php

namespace App\Http\Services;

use App\Http\Repositories\HiburanRepository;
use Illuminate\Support\Facades\Storage;

class HiburanService
{
    private $hiburanRepository;

    public function __construct(HiburanRepository $hiburanRepository)
    {
        $this->hiburanRepository = $hiburanRepository;
    }

    public function listHiburan()
    {
        try {
            return $this->hiburanRepository->listHiburan();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailHiburan($id)
    {
        try {
            return $this->hiburanRepository->detailHiburan($id);
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
            return $this->hiburanRepository->createHiburan($requestData);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateHiburan($requestData, $id)
    {
        try {
            return $this->hiburanRepository->updateHiburan($requestData, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateHiburan($id)
    {
        try {
            return $this->hiburanRepository->validateHiburan($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteHiburan($id)
    {
        try {
            return $this->hiburanRepository->deleteHiburan($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
}
