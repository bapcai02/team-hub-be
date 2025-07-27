<?php

namespace App\Domain\User\Repositories;

interface LeaveRepositoryInterface
{
    /**
     * Get all leave requests.
     * @return array
     */
    public function all(): array;

    /**
     * Find a leave request by ID.
     * @param int|string $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create a new leave request.
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a leave request.
     * @param int|string $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a leave request.
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Find leave requests by employee ID.
     * @param int|string $employeeId
     * @return array
     */
    public function findByEmployeeId($employeeId): array;

    /**
     * Get all leave requests (admin/manager view).
     * @param string|null $status
     * @param int|string|null $departmentId
     * @return array
     */
    public function getAllLeaves($status = null, $departmentId = null): array;

    /**
     * Approve or reject a leave request.
     * @param int|string $id
     * @param array $data
     * @return mixed
     */
    public function approve($id, array $data);

    /**
     * Get leave balance for an employee.
     * @param int|string $employeeId
     * @return mixed
     */
    public function getLeaveBalance($employeeId);

    /**
     * Get leave calendar for a month.
     * @param int $month
     * @param int $year
     * @param int|string|null $departmentId
     * @return array
     */
    public function getLeaveCalendar($month, $year, $departmentId = null): array;
} 