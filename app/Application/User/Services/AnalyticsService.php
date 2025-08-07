<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\AnalyticsRepositoryInterface;
use App\Domain\User\Repositories\EmployeeRepositoryInterface;
use App\Domain\User\Repositories\AttendanceRepositoryInterface;
use App\Domain\User\Repositories\ExpenseRepositoryInterface;
use App\Domain\User\Repositories\PayrollRepositoryInterface;
use App\Domain\User\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnalyticsService
{
    public function __construct(
        protected AnalyticsRepositoryInterface $analyticsRepository,
        protected EmployeeRepositoryInterface $employeeRepository,
        protected AttendanceRepositoryInterface $attendanceRepository,
        protected ExpenseRepositoryInterface $expenseRepository,
        protected PayrollRepositoryInterface $payrollRepository,
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Get comprehensive analytics data.
     */
    public function getComprehensiveAnalytics(string $period = 'month', ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

            return [
                'overview' => $this->getOverviewMetrics($startDate, $endDate),
                'employee_analytics' => $this->getEmployeeAnalyticsData($startDate, $endDate),
                'financial_analytics' => $this->getFinancialAnalyticsData($startDate, $endDate),
                'project_analytics' => $this->getProjectAnalyticsData($startDate, $endDate),
                'attendance_analytics' => $this->getAttendanceAnalyticsData($startDate, $endDate),
                'trends' => $this->getTrendAnalysis($startDate, $endDate),
                'kpis' => $this->buildKPIMetrics($startDate, $endDate)
            ];
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getComprehensiveAnalytics - Error getting comprehensive analytics', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get employee performance analytics.
     */
    public function getEmployeeAnalytics(?int $employeeId = null, string $period = 'month'): array
    {
        try {
            $startDate = $this->getStartDateByPeriod($period);
            $endDate = Carbon::now();

            return [
                'performance_metrics' => $this->getEmployeePerformanceMetrics($employeeId, $startDate, $endDate),
                'attendance_analysis' => $this->getEmployeeAttendanceAnalysis($employeeId, $startDate, $endDate),
                'productivity_trends' => $this->getEmployeeProductivityTrends($employeeId, $startDate, $endDate),
                'skill_analysis' => $this->getEmployeeSkillAnalysis($employeeId),
                'comparison_data' => $this->getEmployeeComparisonData($employeeId, $startDate, $endDate)
            ];
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getEmployeeAnalytics - Error getting employee analytics', [
                'error' => $e->getMessage(),
                'employee_id' => $employeeId
            ]);
            throw $e;
        }
    }

    /**
     * Get financial analytics.
     */
    public function getFinancialAnalytics(string $period = 'month', ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : $this->getStartDateByPeriod($period);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

            return [
                'revenue_analysis' => $this->getRevenueAnalysis($startDate, $endDate),
                'expense_analysis' => $this->getExpenseAnalysis($startDate, $endDate),
                'profitability_metrics' => $this->getProfitabilityMetrics($startDate, $endDate),
                'cash_flow_analysis' => $this->getCashFlowAnalysis($startDate, $endDate),
                'budget_variance' => $this->getBudgetVarianceAnalysis($startDate, $endDate),
                'financial_ratios' => $this->getFinancialRatios($startDate, $endDate)
            ];
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getFinancialAnalytics - Error getting financial analytics', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get project analytics.
     */
    public function getProjectAnalytics(?int $projectId = null, string $period = 'month'): array
    {
        try {
            $startDate = $this->getStartDateByPeriod($period);
            $endDate = Carbon::now();

            return [
                'project_performance' => $this->getProjectPerformanceMetrics($projectId, $startDate, $endDate),
                'resource_utilization' => $this->getResourceUtilization($projectId, $startDate, $endDate),
                'timeline_analysis' => $this->getProjectTimelineAnalysis($projectId, $startDate, $endDate),
                'cost_analysis' => $this->getProjectCostAnalysis($projectId, $startDate, $endDate),
                'risk_metrics' => $this->getProjectRiskMetrics($projectId),
                'quality_metrics' => $this->getProjectQualityMetrics($projectId, $startDate, $endDate)
            ];
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getProjectAnalytics - Error getting project analytics', [
                'error' => $e->getMessage(),
                'project_id' => $projectId
            ]);
            throw $e;
        }
    }

    /**
     * Get attendance analytics.
     */
    public function getAttendanceAnalytics(string $period = 'month', ?int $departmentId = null): array
    {
        try {
            $startDate = $this->getStartDateByPeriod($period);
            $endDate = Carbon::now();

            return [
                'attendance_trends' => $this->getAttendanceTrends($startDate, $endDate, $departmentId),
                'overtime_analysis' => $this->getOvertimeAnalysis($startDate, $endDate, $departmentId),
                'late_attendance_analysis' => $this->getLateAttendanceAnalysis($startDate, $endDate, $departmentId),
                'department_comparison' => $this->getDepartmentAttendanceComparison($startDate, $endDate),
                'attendance_patterns' => $this->getAttendancePatterns($startDate, $endDate, $departmentId),
                'productivity_correlation' => $this->getAttendanceProductivityCorrelation($startDate, $endDate, $departmentId)
            ];
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getAttendanceAnalytics - Error getting attendance analytics', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get custom report data.
     */
    public function getCustomReport(string $reportType, array $filters = []): array
    {
        try {
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
                    throw new \Exception('Invalid report type');
            }
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getCustomReport - Error getting custom report', [
                'error' => $e->getMessage(),
                'report_type' => $reportType
            ]);
            throw $e;
        }
    }

    /**
     * Export analytics report.
     */
    public function exportAnalyticsReport(string $reportType, string $format = 'csv', array $filters = []): mixed
    {
        try {
            $data = $this->getCustomReport($reportType, $filters);
            
            if ($format === 'csv') {
                return $this->exportToCSV($data, $reportType);
            } else {
                throw new \Exception('Unsupported export format');
            }
        } catch (\Exception $e) {
            Log::error('AnalyticsService::exportAnalyticsReport - Error exporting analytics report', [
                'error' => $e->getMessage(),
                'report_type' => $reportType,
                'format' => $format
            ]);
            throw $e;
        }
    }

    /**
     * Get KPI metrics.
     */
    public function getKPIMetrics(string $period = 'month'): array
    {
        try {
            $startDate = $this->getStartDateByPeriod($period);
            $endDate = Carbon::now();

            return $this->buildKPIMetrics($startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getKPIMetrics - Error getting KPI metrics', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get trend analysis.
     */
    public function getTrendAnalysis(string $metric, string $period = 'month'): array
    {
        try {
            $startDate = $this->getStartDateByPeriod($period);
            $endDate = Carbon::now();

            return $this->analyticsRepository->getTrendAnalysis($metric, $startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('AnalyticsService::getTrendAnalysis - Error getting trend analysis', [
                'error' => $e->getMessage(),
                'metric' => $metric
            ]);
            throw $e;
        }
    }

    // Private helper methods
    private function getStartDateByPeriod(string $period): Carbon
    {
        return match ($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth()
        };
    }

    private function getOverviewMetrics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_employees' => $this->employeeRepository->getTotalCount(),
            'active_projects' => $this->projectRepository->getActiveCount(),
            'total_expenses' => $this->expenseRepository->getTotalAmount(),
            'attendance_rate' => 85.5,
            'project_completion_rate' => 75.2,
            'revenue_growth' => 12.5
        ];
    }

    // Mock data methods for demonstration
    private function getEmployeeAnalyticsData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'performance_distribution' => [
                'excellent' => 25,
                'good' => 45,
                'average' => 20,
                'below_average' => 10
            ],
            'skill_gaps' => [
                'technical_skills' => ['PHP', 'React', 'DevOps'],
                'soft_skills' => ['Leadership', 'Communication'],
                'certifications' => ['PMP', 'AWS', 'Azure']
            ],
            'turnover_rate' => 8.5,
            'productivity_trends' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'data' => [85, 87, 89, 92]
            ]
        ];
    }

    private function getFinancialAnalyticsData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'revenue_trends' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [100000, 120000, 110000, 130000, 140000, 150000]
            ],
            'expense_categories' => [
                'travel' => 25000,
                'office' => 15000,
                'marketing' => 30000,
                'training' => 20000,
                'utilities' => 10000
            ],
            'profit_margins' => 25.5,
            'cash_flow_analysis' => [
                'operating_cash_flow' => 120000,
                'investing_cash_flow' => -50000,
                'financing_cash_flow' => -30000,
                'net_cash_flow' => 40000
            ]
        ];
    }

    private function getProjectAnalyticsData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'project_status_distribution' => [
                'active' => 8,
                'completed' => 12,
                'on_hold' => 3,
                'cancelled' => 1
            ],
            'resource_utilization' => [
                'utilization_rate' => 78.5,
                'overallocation' => 15.2,
                'underallocation' => 6.3
            ],
            'timeline_performance' => [
                'on_time' => 65,
                'delayed' => 25,
                'ahead_of_schedule' => 10
            ],
            'cost_variance' => [
                'under_budget' => 40,
                'on_budget' => 35,
                'over_budget' => 25
            ]
        ];
    }

    private function getAttendanceAnalyticsData(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'attendance_trends' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'data' => [95, 92, 88, 94, 90]
            ],
            'overtime_analysis' => [
                'total_overtime_hours' => 120,
                'average_overtime_per_employee' => 8.5,
                'overtime_cost' => 15000
            ],
            'department_comparison' => [
                'IT' => 92,
                'HR' => 88,
                'Finance' => 90,
                'Marketing' => 85,
                'Sales' => 87
            ],
            'attendance_patterns' => [
                'early_arrivals' => 30,
                'on_time' => 55,
                'late_arrivals' => 15
            ]
        ];
    }

    private function buildTrendAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'employee_growth' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [20, 22, 23, 25, 26, 28]
            ],
            'revenue_growth' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [100000, 120000, 110000, 130000, 140000, 150000]
            ],
            'project_completion' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [2, 3, 1, 4, 2, 3]
            ]
        ];
    }

    private function buildKPIMetrics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'employee_kpis' => [
                'employee_satisfaction' => 85.5,
                'turnover_rate' => 8.5,
                'training_completion' => 92.3,
                'performance_average' => 87.2
            ],
            'financial_kpis' => [
                'revenue_growth' => 12.5,
                'profit_margin' => 20.0,
                'expense_ratio' => 80.0,
                'cash_flow_ratio' => 1.2
            ],
            'project_kpis' => [
                'project_completion_rate' => 75.2,
                'on_time_delivery' => 65.0,
                'budget_adherence' => 85.5,
                'customer_satisfaction' => 88.7
            ],
            'operational_kpis' => [
                'attendance_rate' => 92.5,
                'productivity_index' => 87.3,
                'resource_utilization' => 78.5,
                'quality_score' => 91.2
            ]
        ];
    }

    // Additional methods for different analytics sections
    private function getEmployeePerformanceMetrics(?int $employeeId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'performance_score' => 87.5,
            'attendance_rate' => 92.3,
            'productivity_index' => 89.1,
            'quality_score' => 91.8
        ];
    }

    private function getEmployeeAttendanceAnalysis(?int $employeeId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_days' => 22,
            'present_days' => 20,
            'absent_days' => 1,
            'late_days' => 1,
            'overtime_hours' => 12.5
        ];
    }

    private function getEmployeeProductivityTrends(?int $employeeId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'data' => [85, 87, 89, 92]
        ];
    }

    private function getEmployeeSkillAnalysis(?int $employeeId): array
    {
        return [
            'technical_skills' => ['PHP', 'Laravel', 'MySQL'],
            'soft_skills' => ['Communication', 'Leadership'],
            'certifications' => ['AWS', 'PMP'],
            'skill_gaps' => ['DevOps', 'React']
        ];
    }

    private function getEmployeeComparisonData(?int $employeeId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'department_average' => 85.2,
            'company_average' => 87.1,
            'peer_comparison' => 'Above Average'
        ];
    }

    private function getRevenueAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_revenue' => 1500000,
            'revenue_growth' => 12.5,
            'revenue_by_source' => [
                'product_sales' => 800000,
                'services' => 500000,
                'consulting' => 200000
            ]
        ];
    }

    private function getExpenseAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_expenses' => 1200000,
            'expense_growth' => 8.5,
            'expense_by_category' => [
                'personnel' => 600000,
                'operational' => 300000,
                'marketing' => 200000,
                'administrative' => 100000
            ]
        ];
    }

    private function getProfitabilityMetrics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'gross_profit' => 300000,
            'net_profit' => 250000,
            'profit_margin' => 20.0,
            'roi' => 15.5
        ];
    }

    private function getCashFlowAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'operating_cash_flow' => 120000,
            'investing_cash_flow' => -50000,
            'financing_cash_flow' => -30000,
            'net_cash_flow' => 40000
        ];
    }

    private function getBudgetVarianceAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'budget_variance' => 5.2,
            'favorable_variance' => 3.1,
            'unfavorable_variance' => 2.1
        ];
    }

    private function getFinancialRatios(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'current_ratio' => 1.5,
            'debt_to_equity' => 0.3,
            'return_on_equity' => 18.5,
            'return_on_assets' => 12.3
        ];
    }

    private function getProjectPerformanceMetrics(?int $projectId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'completion_percentage' => 75.5,
            'timeline_adherence' => 85.2,
            'budget_adherence' => 92.1,
            'quality_score' => 88.7
        ];
    }

    private function getResourceUtilization(?int $projectId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'utilization_rate' => 78.5,
            'overallocation' => 15.2,
            'underallocation' => 6.3
        ];
    }

    private function getProjectTimelineAnalysis(?int $projectId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'on_time' => 65,
            'delayed' => 25,
            'ahead_of_schedule' => 10
        ];
    }

    private function getProjectCostAnalysis(?int $projectId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'planned_cost' => 100000,
            'actual_cost' => 95000,
            'cost_variance' => 5000,
            'cost_performance_index' => 1.05
        ];
    }

    private function getProjectRiskMetrics(?int $projectId): array
    {
        return [
            'risk_level' => 'Medium',
            'risk_score' => 65,
            'risk_factors' => ['Timeline', 'Budget', 'Resources']
        ];
    }

    private function getProjectQualityMetrics(?int $projectId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'defect_rate' => 2.5,
            'customer_satisfaction' => 88.7,
            'quality_score' => 91.2
        ];
    }

    private function getAttendanceTrends(Carbon $startDate, Carbon $endDate, ?int $departmentId): array
    {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            'data' => [95, 92, 88, 94, 90]
        ];
    }

    private function getOvertimeAnalysis(Carbon $startDate, Carbon $endDate, ?int $departmentId): array
    {
        return [
            'total_overtime_hours' => 120,
            'average_overtime_per_employee' => 8.5,
            'overtime_cost' => 15000
        ];
    }

    private function getLateAttendanceAnalysis(Carbon $startDate, Carbon $endDate, ?int $departmentId): array
    {
        return [
            'total_late_arrivals' => 25,
            'late_arrival_rate' => 5.2,
            'most_late_department' => 'Sales'
        ];
    }

    private function getDepartmentAttendanceComparison(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'IT' => 92,
            'HR' => 88,
            'Finance' => 90,
            'Marketing' => 85,
            'Sales' => 87
        ];
    }

    private function getAttendancePatterns(Carbon $startDate, Carbon $endDate, ?int $departmentId): array
    {
        return [
            'early_arrivals' => 30,
            'on_time' => 55,
            'late_arrivals' => 15
        ];
    }

    private function getAttendanceProductivityCorrelation(Carbon $startDate, Carbon $endDate, ?int $departmentId): array
    {
        return [
            'correlation_coefficient' => 0.75,
            'high_attendance_high_productivity' => 85,
            'low_attendance_low_productivity' => 15
        ];
    }

    // Report generation methods
    private function getEmployeePerformanceReport(array $filters): array
    {
        return [
            'summary' => [
                'total_employees' => 25,
                'average_performance' => 85.5,
                'top_performers' => 5,
                'needs_improvement' => 3
            ],
            'details' => [
                'performance_distribution' => [
                    'excellent' => 25,
                    'good' => 45,
                    'average' => 20,
                    'below_average' => 10
                ],
                'department_performance' => [
                    'IT' => 88.5,
                    'HR' => 82.3,
                    'Finance' => 85.7,
                    'Marketing' => 87.2,
                    'Sales' => 90.1
                ]
            ],
            'recommendations' => [
                'training_needs' => ['Leadership', 'Technical Skills'],
                'recognition_opportunities' => ['Top Performers', 'Innovation'],
                'improvement_areas' => ['Communication', 'Time Management']
            ]
        ];
    }

    private function getFinancialSummaryReport(array $filters): array
    {
        return [
            'summary' => [
                'total_revenue' => 1500000,
                'total_expenses' => 1200000,
                'net_profit' => 300000,
                'profit_margin' => 20.0
            ],
            'details' => [
                'revenue_breakdown' => [
                    'product_sales' => 800000,
                    'services' => 500000,
                    'consulting' => 200000
                ],
                'expense_breakdown' => [
                    'personnel' => 600000,
                    'operational' => 300000,
                    'marketing' => 200000,
                    'administrative' => 100000
                ]
            ],
            'projections' => [
                'next_month_revenue' => 1600000,
                'next_month_expenses' => 1250000,
                'projected_profit' => 350000
            ]
        ];
    }

    private function getProjectStatusReport(array $filters): array
    {
        return [
            'summary' => [
                'total_projects' => 24,
                'active_projects' => 8,
                'completed_projects' => 12,
                'delayed_projects' => 4
            ],
            'details' => [
                'project_status_distribution' => [
                    'active' => 8,
                    'completed' => 12,
                    'on_hold' => 3,
                    'cancelled' => 1
                ],
                'timeline_performance' => [
                    'on_time' => 65,
                    'delayed' => 25,
                    'ahead_of_schedule' => 10
                ]
            ],
            'risks' => [
                'high_risk_projects' => 2,
                'medium_risk_projects' => 5,
                'low_risk_projects' => 17,
                'risk_factors' => ['Timeline', 'Budget', 'Resources', 'Scope']
            ]
        ];
    }

    private function getAttendanceSummaryReport(array $filters): array
    {
        return [
            'summary' => [
                'total_attendance_days' => 500,
                'average_attendance_rate' => 92.5,
                'total_overtime_hours' => 120,
                'late_arrivals' => 25
            ],
            'details' => [
                'attendance_trends' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    'data' => [95, 92, 88, 94, 90]
                ],
                'department_comparison' => [
                    'IT' => 92,
                    'HR' => 88,
                    'Finance' => 90,
                    'Marketing' => 85,
                    'Sales' => 87
                ]
            ],
            'trends' => [
                'monthly_trends' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [90, 91, 89, 93, 92, 94]
                ]
            ]
        ];
    }

    private function getExpenseAnalysisReport(array $filters): array
    {
        return [
            'summary' => [
                'total_expenses' => 1200000,
                'average_expense_per_employee' => 48000,
                'expense_categories' => 5,
                'pending_approvals' => 15
            ],
            'details' => [
                'expense_categories' => [
                    'travel' => 25000,
                    'office' => 15000,
                    'marketing' => 30000,
                    'training' => 20000,
                    'utilities' => 10000
                ],
                'monthly_trends' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [180000, 190000, 175000, 200000, 210000, 120000]
                ]
            ],
            'categories' => [
                'approval_status' => [
                    'approved' => 85,
                    'pending' => 10,
                    'rejected' => 5
                ]
            ]
        ];
    }

    // Export methods
    private function exportToCSV(array $data, string $reportType): mixed
    {
        $filename = $reportType . '_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers and data based on report type
        $this->addCSVData($output, $data, $reportType);

        fclose($output);
        exit;
    }

    private function addCSVData($output, array $data, string $reportType): void
    {
        switch ($reportType) {
            case 'employee_performance':
                fputcsv($output, ['Employee', 'Department', 'Performance Score', 'Rating']);
                foreach ($data['details']['performance_distribution'] as $rating => $count) {
                    fputcsv($output, ['', '', $rating, $count]);
                }
                break;
            case 'financial_summary':
                fputcsv($output, ['Metric', 'Value']);
                fputcsv($output, ['Total Revenue', $data['summary']['total_revenue']]);
                fputcsv($output, ['Total Expenses', $data['summary']['total_expenses']]);
                fputcsv($output, ['Net Profit', $data['summary']['net_profit']]);
                fputcsv($output, ['Profit Margin', $data['summary']['profit_margin'] . '%']);
                break;
            case 'project_status':
                fputcsv($output, ['Status', 'Count']);
                foreach ($data['summary'] as $status => $count) {
                    if (is_numeric($count)) {
                        fputcsv($output, [$status, $count]);
                    }
                }
                break;
            case 'attendance_summary':
                fputcsv($output, ['Metric', 'Value']);
                fputcsv($output, ['Total Attendance Days', $data['summary']['total_attendance_days']]);
                fputcsv($output, ['Average Attendance Rate', $data['summary']['average_attendance_rate'] . '%']);
                fputcsv($output, ['Total Overtime Hours', $data['summary']['total_overtime_hours']]);
                fputcsv($output, ['Late Arrivals', $data['summary']['late_arrivals']]);
                break;
            case 'expense_analysis':
                fputcsv($output, ['Category', 'Amount']);
                foreach ($data['details']['expense_categories'] as $category => $amount) {
                    fputcsv($output, [$category, $amount]);
                }
                break;
        }
    }
} 