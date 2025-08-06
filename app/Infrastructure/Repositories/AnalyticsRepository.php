<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Repositories\AnalyticsRepositoryInterface;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    /**
     * Get trend analysis for a specific metric.
     */
    public function getTrendAnalysis(string $metric, Carbon $startDate, Carbon $endDate): array
    {
        switch ($metric) {
            case 'employee_growth':
                return $this->getEmployeeGrowthTrend($startDate, $endDate);
            case 'revenue_growth':
                return $this->getRevenueGrowthTrend($startDate, $endDate);
            case 'attendance_rate':
                return $this->getAttendanceRateTrend($startDate, $endDate);
            case 'project_completion':
                return $this->getProjectCompletionTrend($startDate, $endDate);
            default:
                return [
                    'labels' => [],
                    'data' => []
                ];
        }
    }

    /**
     * Get employee performance analytics.
     */
    public function getEmployeePerformanceAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        $employees = Employee::with(['user', 'department'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $performanceData = [];
        foreach ($employees as $employee) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalDays = $attendance->count();
            $presentDays = $attendance->where('status', 'present')->count();
            $attendanceRate = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;

            $performanceData[] = [
                'employee_id' => $employee->id,
                'name' => $employee->user->name ?? 'Unknown',
                'department' => $employee->department->name ?? 'Unknown',
                'attendance_rate' => round($attendanceRate, 2),
                'total_hours' => $attendance->sum('total_hours'),
                'overtime_hours' => $attendance->sum('overtime_hours')
            ];
        }

        return [
            'performance_data' => $performanceData,
            'summary' => [
                'total_employees' => count($performanceData),
                'average_attendance_rate' => count($performanceData) > 0 ? 
                    round(array_sum(array_column($performanceData, 'attendance_rate')) / count($performanceData), 2) : 0,
                'total_hours' => array_sum(array_column($performanceData, 'total_hours')),
                'total_overtime_hours' => array_sum(array_column($performanceData, 'overtime_hours'))
            ]
        ];
    }

    /**
     * Get financial analytics.
     */
    public function getFinancialAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        $payrolls = Payroll::whereBetween('created_at', [$startDate, $endDate])->get();

        $expenseByCategory = $expenses->groupBy('type')->map(function ($group) {
            return $group->sum('amount');
        });

        $monthlyExpenses = $expenses->groupBy(function ($expense) {
            return Carbon::parse($expense->date)->format('Y-m');
        })->map(function ($group) {
            return $group->sum('amount');
        });

        return [
            'expenses' => [
                'total' => $expenses->sum('amount'),
                'by_category' => $expenseByCategory,
                'monthly_trend' => $monthlyExpenses
            ],
            'payroll' => [
                'total' => $payrolls->sum('total_amount'),
                'count' => $payrolls->count()
            ],
            'summary' => [
                'total_expenses' => $expenses->sum('amount'),
                'total_payroll' => $payrolls->sum('total_amount'),
                'net_outflow' => $expenses->sum('amount') + $payrolls->sum('total_amount')
            ]
        ];
    }

    /**
     * Get project analytics.
     */
    public function getProjectAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        $projects = Project::whereBetween('created_at', [$startDate, $endDate])->get();

        $statusDistribution = $projects->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        $monthlyProjects = $projects->groupBy(function ($project) {
            return Carbon::parse($project->created_at)->format('Y-m');
        })->map(function ($group) {
            return $group->count();
        });

        return [
            'projects' => [
                'total' => $projects->count(),
                'by_status' => $statusDistribution,
                'monthly_trend' => $monthlyProjects
            ],
            'summary' => [
                'total_projects' => $projects->count(),
                'active_projects' => $projects->where('status', 'active')->count(),
                'completed_projects' => $projects->where('status', 'completed')->count(),
                'delayed_projects' => $projects->where('status', 'delayed')->count()
            ]
        ];
    }

    /**
     * Get attendance analytics.
     */
    public function getAttendanceAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        $attendances = Attendance::with(['employee.user', 'employee.department'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $dailyAttendance = $attendances->groupBy('date')->map(function ($group) {
            return [
                'total' => $group->count(),
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'late' => $group->where('status', 'late')->count()
            ];
        });

        $departmentAttendance = $attendances->groupBy('employee.department.name')->map(function ($group) {
            $total = $group->count();
            $present = $group->where('status', 'present')->count();
            return [
                'total' => $total,
                'present' => $present,
                'rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0
            ];
        });

        return [
            'attendance' => [
                'total_records' => $attendances->count(),
                'daily_data' => $dailyAttendance,
                'by_department' => $departmentAttendance
            ],
            'summary' => [
                'total_days' => $dailyAttendance->count(),
                'average_attendance_rate' => $attendances->count() > 0 ? 
                    round(($attendances->where('status', 'present')->count() / $attendances->count()) * 100, 2) : 0,
                'total_overtime_hours' => $attendances->sum('overtime_hours'),
                'total_late_arrivals' => $attendances->where('status', 'late')->count()
            ]
        ];
    }

    /**
     * Get KPI metrics.
     */
    public function getKPIMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $employees = Employee::whereBetween('created_at', [$startDate, $endDate])->count();
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->get();
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        $projects = Project::whereBetween('created_at', [$startDate, $endDate])->get();

        $attendanceRate = $attendances->count() > 0 ? 
            round(($attendances->where('status', 'present')->count() / $attendances->count()) * 100, 2) : 0;

        $projectCompletionRate = $projects->count() > 0 ? 
            round(($projects->where('status', 'completed')->count() / $projects->count()) * 100, 2) : 0;

        return [
            'employee_kpis' => [
                'total_employees' => $employees,
                'attendance_rate' => $attendanceRate,
                'average_overtime_hours' => $attendances->count() > 0 ? 
                    round($attendances->sum('overtime_hours') / $attendances->count(), 2) : 0
            ],
            'financial_kpis' => [
                'total_expenses' => $expenses->sum('amount'),
                'average_expense_per_day' => $expenses->count() > 0 ? 
                    round($expenses->sum('amount') / $expenses->count(), 2) : 0
            ],
            'project_kpis' => [
                'total_projects' => $projects->count(),
                'completion_rate' => $projectCompletionRate,
                'active_projects' => $projects->where('status', 'active')->count()
            ],
            'operational_kpis' => [
                'attendance_rate' => $attendanceRate,
                'project_completion_rate' => $projectCompletionRate,
                'employee_growth' => $employees
            ]
        ];
    }

    /**
     * Get custom report data.
     */
    public function getCustomReport(string $reportType, array $filters): array
    {
        switch ($reportType) {
            case 'employee_performance':
                return $this->getEmployeePerformanceReport($filters);
            case 'financial_summary':
                return $this->getFinancialSummaryReport($filters);
            case 'project_status':
                return $this->getProjectStatusReport($filters);
            case 'attendance_summary':
                return $this->getAttendanceSummaryReport($filters);
            case 'expense_analysis':
                return $this->getExpenseAnalysisReport($filters);
            default:
                return [];
        }
    }

    // Private helper methods for trend analysis
    private function getEmployeeGrowthTrend(Carbon $startDate, Carbon $endDate): array
    {
        $employees = Employee::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $employees->pluck('date')->toArray(),
            'data' => $employees->pluck('count')->toArray()
        ];
    }

    private function getRevenueGrowthTrend(Carbon $startDate, Carbon $endDate): array
    {
        // Mock data for revenue growth
        $months = [];
        $data = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $months[] = $current->format('Y-m');
            $data[] = rand(100000, 200000); // Mock revenue data
            $current->addMonth();
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getAttendanceRateTrend(Carbon $startDate, Carbon $endDate): array
    {
        $attendances = Attendance::selectRaw('DATE(date) as date, 
            COUNT(*) as total, 
            SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        foreach ($attendances as $attendance) {
            $labels[] = $attendance->date;
            $rate = $attendance->total > 0 ? round(($attendance->present / $attendance->total) * 100, 2) : 0;
            $data[] = $rate;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getProjectCompletionTrend(Carbon $startDate, Carbon $endDate): array
    {
        $projects = Project::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $projects->pluck('date')->toArray(),
            'data' => $projects->pluck('count')->toArray()
        ];
    }

    // Private helper methods for reports
    private function getEmployeePerformanceReport(array $filters): array
    {
        $employees = Employee::with(['user', 'department'])->get();
        
        $performanceData = [];
        foreach ($employees as $employee) {
            $attendance = Attendance::where('employee_id', $employee->id)->get();
            $totalDays = $attendance->count();
            $presentDays = $attendance->where('status', 'present')->count();
            $attendanceRate = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;

            $performanceData[] = [
                'employee_id' => $employee->id,
                'name' => $employee->user->name ?? 'Unknown',
                'department' => $employee->department->name ?? 'Unknown',
                'attendance_rate' => round($attendanceRate, 2),
                'total_hours' => $attendance->sum('total_hours'),
                'overtime_hours' => $attendance->sum('overtime_hours')
            ];
        }

        return [
            'summary' => [
                'total_employees' => count($performanceData),
                'average_attendance_rate' => count($performanceData) > 0 ? 
                    round(array_sum(array_column($performanceData, 'attendance_rate')) / count($performanceData), 2) : 0
            ],
            'details' => $performanceData
        ];
    }

    private function getFinancialSummaryReport(array $filters): array
    {
        $expenses = Expense::all();
        $payrolls = Payroll::all();

        return [
            'summary' => [
                'total_expenses' => $expenses->sum('amount'),
                'total_payroll' => $payrolls->sum('total_amount'),
                'net_outflow' => $expenses->sum('amount') + $payrolls->sum('total_amount')
            ],
            'details' => [
                'expenses_by_category' => $expenses->groupBy('type')->map(function ($group) {
                    return $group->sum('amount');
                }),
                'monthly_expenses' => $expenses->groupBy(function ($expense) {
                    return Carbon::parse($expense->date)->format('Y-m');
                })->map(function ($group) {
                    return $group->sum('amount');
                })
            ]
        ];
    }

    private function getProjectStatusReport(array $filters): array
    {
        $projects = Project::all();

        return [
            'summary' => [
                'total_projects' => $projects->count(),
                'active_projects' => $projects->where('status', 'active')->count(),
                'completed_projects' => $projects->where('status', 'completed')->count(),
                'delayed_projects' => $projects->where('status', 'delayed')->count()
            ],
            'details' => [
                'by_status' => $projects->groupBy('status')->map(function ($group) {
                    return $group->count();
                }),
                'monthly_projects' => $projects->groupBy(function ($project) {
                    return Carbon::parse($project->created_at)->format('Y-m');
                })->map(function ($group) {
                    return $group->count();
                })
            ]
        ];
    }

    private function getAttendanceSummaryReport(array $filters): array
    {
        $attendances = Attendance::with(['employee.user', 'employee.department'])->get();

        $dailyAttendance = $attendances->groupBy('date')->map(function ($group) {
            return [
                'total' => $group->count(),
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'late' => $group->where('status', 'late')->count()
            ];
        });

        return [
            'summary' => [
                'total_records' => $attendances->count(),
                'total_days' => $dailyAttendance->count(),
                'average_attendance_rate' => $attendances->count() > 0 ? 
                    round(($attendances->where('status', 'present')->count() / $attendances->count()) * 100, 2) : 0,
                'total_overtime_hours' => $attendances->sum('overtime_hours')
            ],
            'details' => [
                'daily_data' => $dailyAttendance,
                'by_department' => $attendances->groupBy('employee.department.name')->map(function ($group) {
                    $total = $group->count();
                    $present = $group->where('status', 'present')->count();
                    return [
                        'total' => $total,
                        'present' => $present,
                        'rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0
                    ];
                })
            ]
        ];
    }

    private function getExpenseAnalysisReport(array $filters): array
    {
        $expenses = Expense::all();

        return [
            'summary' => [
                'total_expenses' => $expenses->sum('amount'),
                'total_records' => $expenses->count(),
                'average_expense' => $expenses->count() > 0 ? 
                    round($expenses->sum('amount') / $expenses->count(), 2) : 0
            ],
            'details' => [
                'by_category' => $expenses->groupBy('type')->map(function ($group) {
                    return $group->sum('amount');
                }),
                'monthly_trend' => $expenses->groupBy(function ($expense) {
                    return Carbon::parse($expense->date)->format('Y-m');
                })->map(function ($group) {
                    return $group->sum('amount');
                })
            ]
        ];
    }
} 