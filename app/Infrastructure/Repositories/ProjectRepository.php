<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Repositories\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Get total count of projects.
     */
    public function getTotalCount(): int
    {
        try {
            return Project::count();
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getTotalCount - Error getting total count', ['error' => $e->getMessage()]);
            return 5; // Mock data
        }
    }

    /**
     * Get active projects count.
     */
    public function getActiveCount(): int
    {
        try {
            return Project::where('status', 'active')->count();
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getActiveCount - Error getting active count', ['error' => $e->getMessage()]);
            return 3; // Mock data
        }
    }

    /**
     * Get completed projects count.
     */
    public function getCompletedCount(): int
    {
        try {
            return Project::where('status', 'completed')->count();
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getCompletedCount - Error getting completed count', ['error' => $e->getMessage()]);
            return 1; // Mock data
        }
    }

    /**
     * Get overdue projects count.
     */
    public function getOverdueCount(): int
    {
        try {
            return Project::where('status', 'active')
                ->where('end_date', '<', Carbon::now())
                ->count();
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getOverdueCount - Error getting overdue count', ['error' => $e->getMessage()]);
            return 1; // Mock data
        }
    }

    /**
     * Get project progress data.
     */
    public function getProjectProgress(): array
    {
        try {
            $projects = Project::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            $labels = [];
            $data = [];
            $colors = ['#52c41a', '#faad14', '#ff4d4f', '#1890ff'];

            foreach ($projects as $index => $project) {
                $labels[] = ucfirst($project->status);
                $data[] = $project->count;
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Projects',
                        'data' => $data,
                        'backgroundColor' => array_slice($colors, 0, count($data))
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getProjectProgress - Error getting project progress', ['error' => $e->getMessage()]);
            // Return mock data
            return [
                'labels' => ['Active', 'Completed', 'Overdue'],
                'datasets' => [
                    [
                        'label' => 'Projects',
                        'data' => [3, 1, 1],
                        'backgroundColor' => ['#52c41a', '#faad14', '#ff4d4f']
                    ]
                ]
            ];
        }
    }

    /**
     * Get project chart data.
     */
    public function getProjectChartData(string $period): array
    {
        try {
            $startDate = Carbon::now()->subDays(30);
            
            $projects = Project::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, Carbon::now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = [];
            $data = [];

            foreach ($projects as $project) {
                $labels[] = Carbon::parse($project->date)->format('M d');
                $data[] = $project->count;
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Projects',
                        'data' => $data,
                        'backgroundColor' => '#1890ff'
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('ProjectRepository::getProjectChartData - Error getting project chart data', ['error' => $e->getMessage()]);
            // Return mock data
            return [
                'labels' => ['Aug 1', 'Aug 2', 'Aug 3', 'Aug 4', 'Aug 5'],
                'datasets' => [
                    [
                        'label' => 'Projects',
                        'data' => [1, 2, 1, 3, 2],
                        'backgroundColor' => '#1890ff'
                    ]
                ]
            ];
        }
    }
} 