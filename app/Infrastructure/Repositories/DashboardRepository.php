<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Repositories\DashboardRepositoryInterface;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get recent activities.
     */
    public function getRecentActivities(int $limit = 10): array
    {
        try {
            $activities = [];

            // Get recent attendances
            $recentAttendances = Attendance::with(['employee.user'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recentAttendances as $attendance) {
                $activities[] = [
                    'type' => 'attendance',
                    'title' => 'Attendance recorded',
                    'description' => $attendance->employee->user->name . ' checked in',
                    'time' => $attendance->created_at,
                    'user' => $attendance->employee->user->name,
                    'icon' => 'clock-circle'
                ];
            }

            // Get recent employee additions
            $recentEmployees = Employee::with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recentEmployees as $employee) {
                if ($employee->user) {
                    $activities[] = [
                        'type' => 'employee',
                        'title' => 'New employee added',
                        'description' => $employee->user->name . ' joined the company',
                        'time' => $employee->created_at,
                        'user' => $employee->user->name,
                        'icon' => 'user-add'
                    ];
                }
            }

            // Get recent expenses
            $recentExpenses = Expense::with(['employee.user'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recentExpenses as $expense) {
                $activities[] = [
                    'type' => 'expense',
                    'title' => 'Expense submitted',
                    'description' => $expense->title . ' - $' . number_format($expense->amount, 2),
                    'time' => $expense->created_at,
                    'user' => $expense->employee->user->name,
                    'icon' => 'dollar'
                ];
            }

            // Sort by time and return limited results
            usort($activities, function($a, $b) {
                return $b['time']->timestamp - $a['time']->timestamp;
            });

            return array_slice($activities, 0, $limit);
        } catch (\Exception $e) {
            Log::error('DashboardRepository::getRecentActivities - Error getting recent activities', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        try {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            return [
                'total_employees' => Employee::count(),
                'active_projects' => Project::where('status', 'active')->count(),
                'total_expenses' => Expense::sum('amount'),
                'total_payroll' => Payroll::sum('net_salary'),
                'attendance_rate' => $this->getAttendanceRate(),
                'project_completion_rate' => $this->getProjectCompletionRate()
            ];
        } catch (\Exception $e) {
            Log::error('DashboardRepository::getDashboardStats - Error getting dashboard stats', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get chart data.
     */
    public function getChartData(string $type, string $period): array
    {
        try {
            switch ($type) {
                case 'attendance':
                    return $this->getAttendanceChartData($period);
                case 'expenses':
                    return $this->getExpenseChartData($period);
                case 'projects':
                    return $this->getProjectChartData($period);
                case 'employees':
                    return $this->getEmployeeChartData($period);
                default:
                    return $this->getAttendanceChartData($period);
            }
        } catch (\Exception $e) {
            Log::error('DashboardRepository::getChartData - Error getting chart data', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get attendance chart data.
     */
    private function getAttendanceChartData(string $period): array
    {
        $days = [];
        $present = [];
        $absent = [];
        $late = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $attendance = $this->getAttendanceByDate($date->toDateString());
            
            $days[] = $date->format('M d');
            $present[] = $attendance['present'] ?? 0;
            $absent[] = $attendance['absent'] ?? 0;
            $late[] = $attendance['late'] ?? 0;
        }

        return [
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'backgroundColor' => '#52c41a'
                ],
                [
                    'label' => 'Absent',
                    'data' => $absent,
                    'backgroundColor' => '#ff4d4f'
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'backgroundColor' => '#faad14'
                ]
            ]
        ];
    }

    /**
     * Get expense chart data.
     */
    private function getExpenseChartData(string $period): array
    {
        $expenses = Expense::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [
                Carbon::now()->subDays(30),
                Carbon::now()
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        foreach ($expenses as $expense) {
            $labels[] = Carbon::parse($expense->date)->format('M d');
            $data[] = $expense->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Expenses',
                    'data' => $data,
                    'backgroundColor' => '#1890ff'
                ]
            ]
        ];
    }

    /**
     * Get project chart data.
     */
    private function getProjectChartData(string $period): array
    {
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
    }

    /**
     * Get employee chart data.
     */
    private function getEmployeeChartData(string $period): array
    {
        $employees = Employee::join('departments', 'employees.department_id', '=', 'departments.id')
            ->selectRaw('departments.name as department, COUNT(*) as count')
            ->groupBy('departments.name')
            ->get();

        $labels = [];
        $data = [];

        foreach ($employees as $employee) {
            $labels[] = $employee->department;
            $data[] = $employee->count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => $data,
                    'backgroundColor' => '#722ed1'
                ]
            ]
        ];
    }

    /**
     * Get attendance by date.
     */
    private function getAttendanceByDate(string $date): array
    {
        $attendance = Attendance::where('date', $date)->get();
        
        return [
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'total' => $attendance->count()
        ];
    }

    /**
     * Get attendance rate.
     */
    private function getAttendanceRate(): float
    {
        $today = Carbon::today();
        $attendance = $this->getAttendanceByDate($today->toDateString());
        
        $total = $attendance['present'] + $attendance['absent'] + $attendance['late'];
        
        if ($total === 0) {
            return 0;
        }
        
        return round(($attendance['present'] / $total) * 100, 2);
    }

    /**
     * Get project completion rate.
     */
    private function getProjectCompletionRate(): float
    {
        $total = Project::count();
        $completed = Project::where('status', 'completed')->count();
        
        if ($total === 0) {
            return 0;
        }
        
        return round(($completed / $total) * 100, 2);
    }
} 