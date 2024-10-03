<?php

namespace App\Http\Services;

use App\Http\Repositories\HotelRepository;

class HotelService
{
    private $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function listDataHotel()
    {
        try {
            return $this->hotelRepository->listDataHotel();
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
            return $this->hotelRepository->detailDataHotel($id);
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
            return $this->hotelRepository->inputDataHotel($dataRequest);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataHotel($dataRequest, $id)
    {
        try {
            return $this->hotelRepository->updateDataHotel($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function validateDataHotel($id)
    {
        try {
            return $this->hotelRepository->validateDataHotel($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataHotel($id)
    {
        try {
            return $this->hotelRepository->deleteDataHotel($id);
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
