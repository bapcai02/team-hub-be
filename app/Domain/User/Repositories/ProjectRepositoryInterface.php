<?php

namespace App\Domain\User\Repositories;

interface ProjectRepositoryInterface
{
    /**
     * Get total count of projects.
     */
    public function getTotalCount(): int;

    /**
     * Get active projects count.
     */
    public function getActiveCount(): int;

    /**
     * Get completed projects count.
     */
    public function getCompletedCount(): int;

    /**
     * Get overdue projects count.
     */
    public function getOverdueCount(): int;

    /**
     * Get project progress data.
     */
    public function getProjectProgress(): array;

    /**
     * Get project chart data.
     */
    public function getProjectChartData(string $period): array;
} 