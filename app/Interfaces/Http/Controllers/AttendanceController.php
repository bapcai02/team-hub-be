<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\AttendanceService;
use App\Interfaces\Http\Requests\User\CheckInRequest;
use App\Interfaces\Http\Requests\User\CheckOutRequest;
use App\Interfaces\Http\Requests\User\BreakRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController
{
    public function __construct(protected AttendanceService $attendanceService) {}

    /**
     * Check in for attendance.
     */
    public function checkIn(CheckInRequest $request)
    {
        try {
            $data = $request->validated();
            $data['employee_id'] = $request->user()->employee->id ?? null;
            $data['check_in_time'] = now();
            $data['date'] = now()->toDateString();
            $data['ip_address'] = $request->ip();
            
            if (!$data['employee_id']) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendance = $this->attendanceService->checkIn($data);
            return ApiResponseHelper::responseApi(['attendance' => $attendance], 'check_in_success', 201);
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Check out for attendance.
     */
    public function checkOut(CheckOutRequest $request)
    {
        try {
            $data = $request->validated();
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendance = $this->attendanceService->checkOut($employeeId, $data);
            return ApiResponseHelper::responseApi(['attendance' => $attendance], 'check_out_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Start break.
     */
    public function startBreak(BreakRequest $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendance = $this->attendanceService->startBreak($employeeId);
            return ApiResponseHelper::responseApi(['attendance' => $attendance], 'break_start_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * End break.
     */
    public function endBreak(BreakRequest $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendance = $this->attendanceService->endBreak($employeeId);
            return ApiResponseHelper::responseApi(['attendance' => $attendance], 'break_end_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get today's attendance.
     */
    public function getTodayAttendance(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendance = $this->attendanceService->getTodayAttendance($employeeId);
            return ApiResponseHelper::responseApi(['attendance' => $attendance], 'today_attendance_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get attendance history.
     */
    public function getHistory(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $attendances = $this->attendanceService->getAttendanceHistory($employeeId, $startDate, $endDate);
            return ApiResponseHelper::responseApi(['attendances' => $attendances], 'attendance_history_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get attendance summary.
     */
    public function getSummary(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id ?? null;
            $month = $request->query('month', now()->month);
            $year = $request->query('year', now()->year);
            
            if (!$employeeId) {
                return ApiResponseHelper::responseApi([], 'employee_not_found', 404);
            }
            
            $summary = $this->attendanceService->getAttendanceSummary($employeeId, $month, $year);
            return ApiResponseHelper::responseApi(['summary' => $summary], 'attendance_summary_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Admin: Get all employees attendance for a date.
     */
    public function getAllAttendance(Request $request)
    {
        try {
            $date = $request->query('date', now()->toDateString());
            $attendances = $this->attendanceService->getAllAttendanceByDate($date);
            return ApiResponseHelper::responseApi(['attendances' => $attendances], 'all_attendance_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}