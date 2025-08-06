<?php

namespace App\Domain\User\Repositories;

interface DashboardRepositoryInterface
{
    /**
     * Get recent activities.
     */
    public function getRecentActivities(int $limit = 10): array;

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array;

    /**
     * Get chart data.
     */
    public function getChartData(string $type, string $period): array;
} 