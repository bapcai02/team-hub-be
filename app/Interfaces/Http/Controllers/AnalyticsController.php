<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\User\Services\AnalyticsService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    /**
     * Get comprehensive analytics data.
     */
    public function getAnalytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            $analytics = $this->analyticsService->getComprehensiveAnalytics($period, $startDate, $endDate);
            
            return ApiResponseHelper::responseApi(['analytics' => $analytics], 'analytics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getAnalytics - Error getting analytics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get employee performance analytics.
     */
    public function getEmployeeAnalytics(Request $request)
    {
        try {
            $employeeId = $request->get('employee_id');
            $period = $request->get('period', 'month');
            
            $analytics = $this->analyticsService->getEmployeeAnalytics($employeeId, $period);
            
            return ApiResponseHelper::responseApi(['analytics' => $analytics], 'employee_analytics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getEmployeeAnalytics - Error getting employee analytics', [
                'error' => $e->getMessage(),
                'employee_id' => $employeeId ?? null
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get financial analytics.
     */
    public function getFinancialAnalytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            $analytics = $this->analyticsService->getFinancialAnalytics($period, $startDate, $endDate);
            
            return ApiResponseHelper::responseApi(['analytics' => $analytics], 'financial_analytics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getFinancialAnalytics - Error getting financial analytics', [
                'error' => $e->getMessage()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get project analytics.
     */
    public function getProjectAnalytics(Request $request)
    {
        try {
            $projectId = $request->get('project_id');
            $period = $request->get('period', 'month');
            
            $analytics = $this->analyticsService->getProjectAnalytics($projectId, $period);
            
            return ApiResponseHelper::responseApi(['analytics' => $analytics], 'project_analytics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getProjectAnalytics - Error getting project analytics', [
                'error' => $e->getMessage(),
                'project_id' => $projectId ?? null
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get attendance analytics.
     */
    public function getAttendanceAnalytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $departmentId = $request->get('department_id');
            
            $analytics = $this->analyticsService->getAttendanceAnalytics($period, $departmentId);
            
            return ApiResponseHelper::responseApi(['analytics' => $analytics], 'attendance_analytics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getAttendanceAnalytics - Error getting attendance analytics', [
                'error' => $e->getMessage()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get custom report data.
     */
    public function getCustomReport(Request $request)
    {
        try {
            $reportType = $request->get('report_type');
            $filters = $request->all();
            
            $report = $this->analyticsService->getCustomReport($reportType, $filters);
            
            return ApiResponseHelper::responseApi(['report' => $report], 'custom_report_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getCustomReport - Error getting custom report', [
                'error' => $e->getMessage(),
                'report_type' => $reportType ?? null
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Export analytics report.
     */
    public function exportAnalyticsReport(Request $request)
    {
        try {
            $reportType = $request->get('report_type');
            $format = $request->get('format', 'csv');
            $filters = $request->all();
            
            $report = $this->analyticsService->exportAnalyticsReport($reportType, $format, $filters);
            
            return $report;
        } catch (\Exception $e) {
            Log::error('AnalyticsController::exportAnalyticsReport - Error exporting analytics report', [
                'error' => $e->getMessage(),
                'report_type' => $reportType ?? null
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get KPI metrics.
     */
    public function getKPIMetrics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            
            $kpis = $this->analyticsService->getKPIMetrics($period);
            
            return ApiResponseHelper::responseApi(['kpis' => $kpis], 'kpi_metrics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getKPIMetrics - Error getting KPI metrics', [
                'error' => $e->getMessage()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get trend analysis.
     */
    public function getTrendAnalysis(Request $request)
    {
        try {
            $metric = $request->get('metric');
            $period = $request->get('period', 'month');
            
            $trends = $this->analyticsService->getTrendAnalysis($metric, $period);
            
            return ApiResponseHelper::responseApi(['trends' => $trends], 'trend_analysis_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('AnalyticsController::getTrendAnalysis - Error getting trend analysis', [
                'error' => $e->getMessage(),
                'metric' => $metric ?? null
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }
} 