<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\EmployeeRepositoryInterface;
use App\Domain\User\Entities\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
    ) {}

    /**
     * Get all employees with filters.
     */
    public function getAllEmployees(array $filters = []): array
    {
        try {
            return $this->employeeRepository->getAllWithFilters($filters);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getAllEmployees - Error getting employees', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Find employee by ID.
     */
    public function findById(int $id): ?Employee
    {
        try {
            return $this->employeeRepository->findById($id);
        } catch (\Exception $e) {
            Log::error('EmployeeService::findById - Error finding employee', ['error' => $e->getMessage(), 'id' => $id]);
            throw $e;
        }
    }

    /**
     * Create new employee.
     */
    public function create(array $data): Employee
    {
        try {
            DB::beginTransaction();
            
            // Validate unique user
            if ($this->employeeRepository->findByUserId($data['user_id'])) {
                throw new \Exception('User already has employee record');
            }
            
            $employee = $this->employeeRepository->create($data);
            
            DB::commit();
            return $employee;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeService::create - Error creating employee', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update employee.
     */
    public function update(int $id, array $data): ?Employee
    {
        try {
            DB::beginTransaction();
            
            $employee = $this->employeeRepository->findById($id);
            if (!$employee) {
                return null;
            }
            
            // Check if user_id is unique (if changed)
            if (isset($data['user_id']) && $data['user_id'] !== $employee->user_id) {
                if ($this->employeeRepository->findByUserId($data['user_id'])) {
                    throw new \Exception('User already has employee record');
                }
            }
            
            $updatedEmployee = $this->employeeRepository->update($id, $data);
            
            DB::commit();
            return $updatedEmployee;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeService::update - Error updating employee', ['error' => $e->getMessage(), 'id' => $id, 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Delete employee.
     */
    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();
            
            $employee = $this->employeeRepository->findById($id);
            if (!$employee) {
                return false;
            }
            
            // Soft delete - just update status to terminated
            $result = $this->employeeRepository->update($id, ['status' => 'terminated']);
            
            DB::commit();
            return $result !== null;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeService::delete - Error deleting employee', ['error' => $e->getMessage(), 'id' => $id]);
            throw $e;
        }
    }

    /**
     * Get employee statistics.
     */
    public function getEmployeeStats(): array
    {
        try {
            return $this->employeeRepository->getEmployeeStats();
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeeStats - Error getting employee stats', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get employee time logs history.
     */
    public function getEmployeeTimeLogs(int $employeeId, ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            return $this->employeeRepository->getEmployeeTimeLogs($employeeId, $startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeeTimeLogs - Error getting employee time logs', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Add time log for employee.
     */
    public function addEmployeeTimeLog(int $employeeId, array $data): array
    {
        try {
            return $this->employeeRepository->addEmployeeTimeLog($employeeId, $data);
        } catch (\Exception $e) {
            Log::error('EmployeeService::addEmployeeTimeLog - Error adding employee time log', ['error' => $e->getMessage(), 'employee_id' => $employeeId, 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Get employee leave history.
     */
    public function getEmployeeLeaves(int $employeeId, ?string $status = null, ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            return $this->employeeRepository->getEmployeeLeaves($employeeId, $status, $startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeeLeaves - Error getting employee leaves', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get employee payroll history.
     */
    public function getEmployeePayrolls(int $employeeId, ?string $month = null, ?string $year = null): array
    {
        try {
            return $this->employeeRepository->getEmployeePayrolls($employeeId, $month, $year);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeePayrolls - Error getting employee payrolls', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get employee performance history.
     */
    public function getEmployeePerformances(int $employeeId, ?string $period = null): array
    {
        try {
            return $this->employeeRepository->getEmployeePerformances($employeeId, $period);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeePerformances - Error getting employee performances', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get employee performance history.
     */
    public function getEmployeeEvaluations(int $employeeId, ?string $period = null): array
    {
        try {
            return $this->employeeRepository->getEmployeeEvaluations($employeeId, $period);
        } catch (\Exception $e) {
            Log::error('EmployeeService::getEmployeeEvaluations - Error getting employee evaluations', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }
} 