<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\User\Services\DashboardService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function getDashboardData(Request $request)
    {
        try {
            Log::info('DashboardController::getDashboardData - Starting to get dashboard data');
            
            $dashboardData = $this->dashboardService->getDashboardData();
            
            Log::info('DashboardController::getDashboardData - Successfully got dashboard data', [
                'data_keys' => array_keys($dashboardData)
            ]);
            
            return ApiResponseHelper::responseApi(['dashboard' => $dashboardData], 'dashboard_data_success');
        } catch (\Exception $e) {
            Log::error('DashboardController::getDashboardData - Error getting dashboard data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    public function getRecentActivities(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $activities = $this->dashboardService->getRecentActivities($limit);
            return ApiResponseHelper::responseApi(['activities' => $activities], 'activities_success');
        } catch (\Exception $e) {
            Log::error('DashboardController::getRecentActivities - Error getting recent activities', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    public function getChartData(Request $request)
    {
        try {
            $type = $request->get('type', 'attendance');
            $period = $request->get('period', 'month');
            $chartData = $this->dashboardService->getChartData($type, $period);
            return ApiResponseHelper::responseApi(['chart' => $chartData], 'chart_data_success');
        } catch (\Exception $e) {
            Log::error('DashboardController::getChartData - Error getting chart data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }
} 