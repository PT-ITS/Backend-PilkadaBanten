<?php

namespace App\Http\Services;

use App\Http\Repositories\DashboardRepository;

class DashboardService
{
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDataDashboard()
    {
        try {
            return $this->dashboardRepository->getDataDashboard();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function exportAll()
    {
        try {
            return $this->dashboardRepository->exportAll();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listAll()
    {
        try {
            return $this->dashboardRepository->listAll();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listAllBySurveyor()
    {
        try {
            return $this->dashboardRepository->listAllBySurveyor();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listAllByPengelola()
    {
        try {
            return $this->dashboardRepository->listAllByPengelola();
        } catch (\Exception $e) {
            return [
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
}
