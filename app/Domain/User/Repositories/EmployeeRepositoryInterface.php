<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\Employee;

interface EmployeeRepositoryInterface
{
    /**
     * Get all employees with filters.
     */
    public function getAllWithFilters(array $filters = []): array;

    /**
     * Find employee by ID.
     */
    public function findById(int $id): ?Employee;

    /**
     * Find employee by user ID.
     */
    public function findByUserId(int $userId): ?Employee;

    /**
     * Find employee by user email.
     */
    public function findByUserEmail(string $email): ?Employee;

    /**
     * Create new employee.
     */
    public function create(array $data): Employee;

    /**
     * Update employee.
     */
    public function update(int $id, array $data): ?Employee;

    /**
     * Delete employee.
     */
    public function delete(int $id): bool;

    /**
     * Get employee statistics.
     */
    public function getEmployeeStats(): array;

    /**
     * Get employee time logs history.
     */
    public function getEmployeeTimeLogs(int $employeeId, ?string $startDate = null, ?string $endDate = null): array;
    public function addEmployeeTimeLog(int $employeeId, array $data): array;

    /**
     * Get employee leave history.
     */
    public function getEmployeeLeaves(int $employeeId, ?string $status = null, ?string $startDate = null, ?string $endDate = null): array;

    /**
     * Get employee payroll history.
     */
    public function getEmployeePayrolls(int $employeeId, ?string $month = null, ?string $year = null): array;

    /**
     * Get employee performance history.
     */
    public function getEmployeePerformances(int $employeeId, ?string $period = null): array;

    /**
     * Get employee evaluations history.
     */
    public function getEmployeeEvaluations(int $employeeId, ?string $period = null): array;
} 