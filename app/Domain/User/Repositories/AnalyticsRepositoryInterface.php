<?php

namespace App\Domain\User\Repositories;

use Carbon\Carbon;

interface AnalyticsRepositoryInterface
{
    /**
     * Get trend analysis for a specific metric.
     */
    public function getTrendAnalysis(string $metric, Carbon $startDate, Carbon $endDate): array;

    /**
     * Get employee performance analytics.
     */
    public function getEmployeePerformanceAnalytics(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get financial analytics.
     */
    public function getFinancialAnalytics(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get project analytics.
     */
    public function getProjectAnalytics(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get attendance analytics.
     */
    public function getAttendanceAnalytics(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get KPI metrics.
     */
    public function getKPIMetrics(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get custom report data.
     */
    public function getCustomReport(string $reportType, array $filters): array;
} 