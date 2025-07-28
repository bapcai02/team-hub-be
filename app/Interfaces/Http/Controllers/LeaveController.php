<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\LeaveService;
use App\Interfaces\Http\Requests\User\StoreLeaveRequest;
use App\Interfaces\Http\Requests\User\UpdateLeaveRequest;
use App\Interfaces\Http\Requests\User\ApproveLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveController
{
    public function __construct(protected LeaveService $leaveService) {}

    /**
     * Get all leave requests for current user.
     */
    public function index(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $leaves = $this->leaveService->getByEmployeeId($employeeId);
            return ApiResponseHelper::responseApi(['leaves' => $leaves], 'leave_list_success');
        } catch (\Throwable $e) {
            Log::error('LeaveController::index - Error getting leave requests', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new leave request.
     */
    public function store(StoreLeaveRequest $request)
    {
        try {
            $data = $request->validated();
            $data['employee_id'] = $request->user()->employee->id ?? null;
            $data['status'] = 'pending';
            
            if (!$data['employee_id']) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $leave = $this->leaveService->create($data);
            return ApiResponseHelper::responseApi(['leave' => $leave], 'leave_request_success', 201);
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get leave request details.
     */
    public function show($id)
    {
        try {
            $leave = $this->leaveService->find($id);
            if (!$leave) {
                return ApiResponseHelper::responseApi([], 'leave_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['leave' => $leave], 'leave_details_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update leave request (only if pending).
     */
    public function update(UpdateLeaveRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leave = $this->leaveService->update($id, $data);
            
            if (!$leave) {
                return ApiResponseHelper::responseApi([], 'leave_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['leave' => $leave], 'leave_update_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Cancel leave request.
     */
    public function cancel($id)
    {
        try {
            $leave = $this->leaveService->cancel($id);
            if (!$leave) {
                return ApiResponseHelper::responseApi([], 'leave_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['leave' => $leave], 'leave_cancel_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Admin: Get all leave requests.
     */
    public function getAllLeaves(Request $request)
    {
        try {
            $status = $request->query('status');
            $departmentId = $request->query('department_id');
            
            $leaves = $this->leaveService->getAllLeaves($status, $departmentId);
            return ApiResponseHelper::responseApi(['leaves' => $leaves], 'all_leaves_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Admin: Approve or reject leave request.
     */
    public function approve(ApproveLeaveRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['approved_by'] = $request->user()->id;
            
            $leave = $this->leaveService->approve($id, $data);
            if (!$leave) {
                return ApiResponseHelper::responseApi([], 'leave_not_found', 404);
            }
            
            $message = $data['status'] === 'approved' ? 'leave_approved_success' : 'leave_rejected_success';
            return ApiResponseHelper::responseApi(['leave' => $leave], $message);
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get leave balance for current user.
     */
    public function getBalance(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $balance = $this->leaveService->getLeaveBalance($employeeId);
            return ApiResponseHelper::responseApi(['balance' => $balance], 'leave_balance_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get leave calendar for a month.
     */
    public function getCalendar(Request $request)
    {
        try {
            $month = $request->query('month', now()->month);
            $year = $request->query('year', now()->year);
            $departmentId = $request->query('department_id');
            
            $calendar = $this->leaveService->getLeaveCalendar($month, $year, $departmentId);
            return ApiResponseHelper::responseApi(['calendar' => $calendar], 'leave_calendar_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}