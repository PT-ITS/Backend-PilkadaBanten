<?php

namespace App\Http\Services;

use App\Http\Repositories\FnbRepository;
use Illuminate\Support\Facades\Storage;

class FnbService
{
    private $fnbRepository;

    public function __construct(FnbRepository $fnbRepository)
    {
        $this->fnbRepository = $fnbRepository;
    }

    public function listFnb()
    {
        try {
            return $this->fnbRepository->listFnb();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailFnb($id)
    {
        try {
            return $this->fnbRepository->detailFnb($id);
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
            return $this->fnbRepository->createFnb($requestData);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateFnb($requestData, $id)
    {
        try {
            return $this->fnbRepository->updateFnb($requestData, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateFnb($id)
    {
        try {
            return $this->fnbRepository->validateFnb($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteFnb($id)
    {
        try {
            return $this->fnbRepository->deleteFnb($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
}
