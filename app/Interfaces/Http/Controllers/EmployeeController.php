<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\EmployeeService;
use App\Interfaces\Http\Requests\User\StoreEmployeeRequest;
use App\Interfaces\Http\Requests\User\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController
{
    public function __construct(protected EmployeeService $employeeService) {}

    /**
     * Get all employees with pagination and filters.
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'search' => $request->query('search'),
                'status' => $request->query('status'),
                'department' => $request->query('department'),
                'position' => $request->query('position'),
            ];
            
            $employees = $this->employeeService->getAllEmployees($filters);
            return ApiResponseHelper::responseApi(['employees' => $employees], 'employees_list_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::index - Error getting employees', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee details by ID.
     */
    public function show($id)
    {
        try {
            $employee = $this->employeeService->findById($id);
            if (!$employee) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['employee' => $employee], 'employee_details_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::show - Error getting employee details', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new employee.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->create($data);
            return ApiResponseHelper::responseApi(['employee' => $employee], 'employee_created_success', 201);
        } catch (\Exception $e) {
            Log::error('EmployeeController::store - Error creating employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            Log::error('EmployeeController::store - Error creating employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update employee details.
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $employee = $this->employeeService->update($id, $data);
            
            if (!$employee) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['employee' => $employee], 'employee_updated_success');
        } catch (\Exception $e) {
            Log::error('EmployeeController::update - Error updating employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            Log::error('EmployeeController::update - Error updating employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete employee.
     */
    public function destroy($id)
    {
        try {
            $result = $this->employeeService->delete($id);
            if (!$result) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'employee_deleted_success');
        } catch (\Exception $e) {
            Log::error('EmployeeController::destroy - Error deleting employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            Log::error('EmployeeController::destroy - Error deleting employee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee statistics.
     */
    public function getStats()
    {
        try {
            $stats = $this->employeeService->getEmployeeStats();
            return ApiResponseHelper::responseApi(['stats' => $stats], 'employee_stats_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getStats - Error getting employee stats', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee time logs history.
     */
    public function getTimeLogs($id, Request $request)
    {
        try {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            
            $timeLogs = $this->employeeService->getEmployeeTimeLogs($id, $startDate, $endDate);
            return ApiResponseHelper::responseApi(['time_logs' => $timeLogs], 'employee_time_logs_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getTimeLogs - Error getting employee time logs', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Add time log for employee.
     */
    public function addTimeLog($id, Request $request)
    {
        try {
            $data = $request->validate([
                'date' => 'required|date',
                'check_in' => 'required|date',
                'check_out' => 'nullable|date|after:check_in',
            ]);

            $data['employee_id'] = $id;
            
            $timeLog = $this->employeeService->addEmployeeTimeLog($id, $data);
            return ApiResponseHelper::responseApi(['time_log' => $timeLog], 'time_log_added_success', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('EmployeeController::addTimeLog - Validation error', ['error' => $e->getMessage()]);
            return ApiResponseHelper::responseApi([], 'validation_error', 422);
        } catch (\Throwable $e) {
            Log::error('EmployeeController::addTimeLog - Error adding time log', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee leave history.
     */
    public function getLeaves($id, Request $request)
    {
        try {
            $status = $request->query('status');
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            
            $leaves = $this->employeeService->getEmployeeLeaves($id, $status, $startDate, $endDate);
            return ApiResponseHelper::responseApi(['leaves' => $leaves], 'employee_leaves_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getLeaves - Error getting employee leaves', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee payroll history.
     */
    public function getPayrolls($id, Request $request)
    {
        try {
            $month = $request->query('month');
            $year = $request->query('year');
            
            $payrolls = $this->employeeService->getEmployeePayrolls($id, $month, $year);
            return ApiResponseHelper::responseApi(['payrolls' => $payrolls], 'employee_payrolls_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getPayrolls - Error getting employee payrolls', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee performance history.
     */
    public function getPerformances($id, Request $request)
    {
        try {
            $period = $request->query('period');
            
            $performances = $this->employeeService->getEmployeePerformances($id, $period);
            return ApiResponseHelper::responseApi(['performances' => $performances], 'employee_performances_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getPerformances - Error getting employee performances', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get employee performance history.
     */
    public function getEvaluations($id, Request $request)
    {
        try {
            $period = $request->query('period');
            
            $evaluations = $this->employeeService->getEmployeeEvaluations($id, $period);
            return ApiResponseHelper::responseApi(['evaluations' => $evaluations], 'employee_evaluations_success');
        } catch (\Throwable $e) {
            Log::error('EmployeeController::getEvaluations - Error getting employee evaluations', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 