<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Repositories\AttendanceRepositoryInterface;
use App\Domain\User\Entities\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    /**
     * Create new attendance record.
     */
    public function create(array $data): Attendance
    {
        try {
            return Attendance::create($data);
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::create - Error creating attendance', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Find attendance by ID.
     */
    public function find($id): ?Attendance
    {
        try {
            return Attendance::find($id);
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::find - Error finding attendance', ['error' => $e->getMessage(), 'id' => $id]);
            throw $e;
        }
    }

    /**
     * Update attendance record.
     */
    public function update($id, array $data): ?Attendance
    {
        try {
            $attendance = Attendance::find($id);
            if (!$attendance) {
                return null;
            }

            $attendance->update($data);
            return $attendance->fresh();
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::update - Error updating attendance', ['error' => $e->getMessage(), 'id' => $id, 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Delete attendance record.
     */
    public function delete($id): bool
    {
        try {
            $attendance = Attendance::find($id);
            if (!$attendance) {
                return false;
            }

            return $attendance->delete();
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::delete - Error deleting attendance', ['error' => $e->getMessage(), 'id' => $id]);
            throw $e;
        }
    }

    /**
     * Get today's attendance for employee.
     */
    public function getTodayAttendance(int $employeeId): ?Attendance
    {
        try {
            return Attendance::where('employee_id', $employeeId)
                ->where('date', now()->toDateString())
                ->first();
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getTodayAttendance - Error getting today attendance', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get attendance by date range for employee.
     */
    public function getAttendanceByDateRange(int $employeeId, string $startDate, string $endDate): array
    {
        try {
            $attendances = Attendance::where('employee_id', $employeeId)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();

            return [
                'data' => $attendances,
                'total' => $attendances->count(),
                'employee_id' => $employeeId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAttendanceByDateRange - Error getting attendance by date range', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get attendance by month for employee.
     */
    public function getAttendanceByMonth(int $employeeId, int $month, int $year): array
    {
        try {
            $attendances = Attendance::where('employee_id', $employeeId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->orderBy('date', 'desc')
                ->get();

            return $attendances->toArray();
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAttendanceByMonth - Error getting attendance by month', ['error' => $e->getMessage(), 'employee_id' => $employeeId]);
            throw $e;
        }
    }

    /**
     * Get all attendance by date.
     */
    public function getAllAttendanceByDate(string $date): array
    {
        try {
            $attendances = Attendance::with(['employee.user'])
                ->where('date', $date)
                ->orderBy('check_in_time', 'asc')
                ->get();

            return [
                'data' => $attendances,
                'total' => $attendances->count(),
                'date' => $date,
            ];
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAllAttendanceByDate - Error getting all attendance by date', ['error' => $e->getMessage(), 'date' => $date]);
            throw $e;
        }
    }

    /**
     * Get all attendance records with filters.
     */
    public function getAllAttendances(array $filters = []): array
    {
        try {
            $query = Attendance::with(['employee.user', 'employee.department']);

            // Apply search filter
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->whereHas('employee.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply department filter
            if (!empty($filters['department'])) {
                $query->whereHas('employee.department', function ($q) use ($filters) {
                    $q->where('name', $filters['department']);
                });
            }

            // Apply date range filter
            if (!empty($filters['start_date'])) {
                $query->where('date', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('date', '<=', $filters['end_date']);
            }

            $attendances = $query->orderBy('date', 'desc')->get();

            return [
                'data' => $attendances,
                'total' => $attendances->count(),
                'filters' => $filters
            ];
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAllAttendances - Error getting all attendances', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get attendance statistics.
     */
    public function getAttendanceStats(): array
    {
        try {
            $totalAttendances = Attendance::count();
            $presentAttendances = Attendance::where('status', 'present')->count();
            $absentAttendances = Attendance::where('status', 'absent')->count();
            $lateAttendances = Attendance::where('status', 'late')->count();
            $halfDayAttendances = Attendance::where('status', 'half_day')->count();

            // Department stats
            $departmentStats = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department', DB::raw('count(*) as count'))
                ->groupBy('departments.name')
                ->get();

            return [
                'total' => $totalAttendances,
                'present' => $presentAttendances,
                'absent' => $absentAttendances,
                'late' => $lateAttendances,
                'half_day' => $halfDayAttendances,
                'departments' => $departmentStats,
            ];
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAttendanceStats - Error getting attendance stats', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get attendance by employee and date.
     */
    public function getAttendanceByEmployeeAndDate(int $employeeId, string $date): ?Attendance
    {
        try {
            return Attendance::where('employee_id', $employeeId)
                ->where('date', $date)
                ->first();
        } catch (\Exception $e) {
            Log::error('AttendanceRepository::getAttendanceByEmployeeAndDate - Error getting attendance by employee and date', ['error' => $e->getMessage(), 'employee_id' => $employeeId, 'date' => $date]);
            throw $e;
        }
    }
} 