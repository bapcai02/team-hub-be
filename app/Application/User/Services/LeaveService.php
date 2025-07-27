<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\LeaveRepositoryInterface;

class LeaveService
{
    protected $leaveRepository;

    public function __construct(LeaveRepositoryInterface $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    /**
     * Get all leave requests for a specific employee.
     *
     * @param int|string $employeeId
     * @return array
     */
    public function getByEmployeeId($employeeId): array
    {
        return $this->leaveRepository->findByEmployeeId($employeeId);
    }

    /**
     * Create a new leave request.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->leaveRepository->create($data);
    }

    /**
     * Find a leave request by ID.
     *
     * @param int|string $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->leaveRepository->find($id);
    }

    /**
     * Update a leave request.
     *
     * @param int|string $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        return $this->leaveRepository->update($id, $data);
    }

    /**
     * Cancel a leave request.
     *
     * @param int|string $id
     * @return mixed
     */
    public function cancel($id)
    {
        return $this->leaveRepository->update($id, ['status' => 'cancelled']);
    }

    /**
     * Get all leave requests (admin/manager view).
     *
     * @param string|null $status
     * @param int|string|null $departmentId
     * @return array
     */
    public function getAllLeaves($status = null, $departmentId = null): array
    {
        return $this->leaveRepository->getAllLeaves($status, $departmentId);
    }

    /**
     * Approve or reject a leave request.
     *
     * @param int|string $id
     * @param array $data
     * @return mixed
     */
    public function approve($id, array $data)
    {
        return $this->leaveRepository->approve($id, $data);
    }

    /**
     * Get leave balance for an employee.
     *
     * @param int|string $employeeId
     * @return mixed
     */
    public function getLeaveBalance($employeeId)
    {
        return $this->leaveRepository->getLeaveBalance($employeeId);
    }

    /**
     * Get leave calendar for a month.
     *
     * @param int $month
     * @param int $year
     * @param int|string|null $departmentId
     * @return array
     */
    public function getLeaveCalendar($month, $year, $departmentId = null): array
    {
        return $this->leaveRepository->getLeaveCalendar($month, $year, $departmentId);
    }
} 