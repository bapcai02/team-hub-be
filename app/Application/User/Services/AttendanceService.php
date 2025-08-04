<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\AttendanceRepositoryInterface;
use App\Domain\User\Entities\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public function __construct(
        protected AttendanceRepositoryInterface $attendanceRepository,
    ) {}

    public function checkIn(array $data): Attendance
    {
        // Check if already checked in today
        $existingAttendance = $this->attendanceRepository->getTodayAttendance($data['employee_id']);
        if ($existingAttendance && $existingAttendance->check_in_time) {
            throw new \Exception('Already checked in today');
        }

        if ($existingAttendance) {
            // Update existing record
            return $this->attendanceRepository->update($existingAttendance->id, $data);
        }

        // Create new attendance record
        return $this->attendanceRepository->create($data);
    }

    public function checkOut(int $employeeId, array $data): Attendance
    {
        $attendance = $this->attendanceRepository->getTodayAttendance($employeeId);
        if (!$attendance) {
            throw new \Exception('No check-in record found for today');
        }

        if ($attendance->check_out_time) {
            throw new \Exception('Already checked out today');
        }

        $data['check_out_time'] = now();
        
        // Calculate total hours
        $checkIn = Carbon::parse($attendance->check_in_time);
        $checkOut = Carbon::parse($data['check_out_time']);
        $totalMinutes = $checkIn->diffInMinutes($checkOut);
        
        // Subtract break time if any
        if ($attendance->break_start_time && $attendance->break_end_time) {
            $breakStart = Carbon::parse($attendance->break_start_time);
            $breakEnd = Carbon::parse($attendance->break_end_time);
            $breakMinutes = $breakStart->diffInMinutes($breakEnd);
            $totalMinutes -= $breakMinutes;
        }
        
        $data['total_hours'] = round($totalMinutes / 60, 2);
        
        // Calculate overtime (assuming 8 hours is standard)
        $standardHours = 8;
        $data['overtime_hours'] = max(0, $data['total_hours'] - $standardHours);

        return $this->attendanceRepository->update($attendance->id, $data);
    }

    public function startBreak(int $employeeId): Attendance
    {
        $attendance = $this->attendanceRepository->getTodayAttendance($employeeId);
        if (!$attendance) {
            throw new \Exception('No check-in record found for today');
        }

        if ($attendance->break_start_time) {
            throw new \Exception('Break already started');
        }

        return $this->attendanceRepository->update($attendance->id, [
            'break_start_time' => now()
        ]);
    }

    public function endBreak(int $employeeId): Attendance
    {
        $attendance = $this->attendanceRepository->getTodayAttendance($employeeId);
        if (!$attendance) {
            throw new \Exception('No check-in record found for today');
        }

        if (!$attendance->break_start_time) {
            throw new \Exception('Break not started yet');
        }

        if ($attendance->break_end_time) {
            throw new \Exception('Break already ended');
        }

        return $this->attendanceRepository->update($attendance->id, [
            'break_end_time' => now()
        ]);
    }

    public function getTodayAttendance(int $employeeId): ?Attendance
    {
        return $this->attendanceRepository->getTodayAttendance($employeeId);
    }

    public function getAttendanceHistory(int $employeeId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?: now()->startOfMonth()->toDateString();
        $endDate = $endDate ?: now()->endOfMonth()->toDateString();
        
        return $this->attendanceRepository->getAttendanceByDateRange($employeeId, $startDate, $endDate);
    }

    public function getAttendanceSummary(int $employeeId, int $month, int $year): array
    {
        $attendances = $this->attendanceRepository->getAttendanceByMonth($employeeId, $month, $year);
        
        $totalDays = count($attendances);
        $totalHours = array_sum(array_column($attendances, 'total_hours'));
        $totalOvertimeHours = array_sum(array_column($attendances, 'overtime_hours'));
        $presentDays = count(array_filter($attendances, fn($a) => $a['status'] === 'present'));
        $lateDays = count(array_filter($attendances, fn($a) => $a['status'] === 'late'));
        $absentDays = count(array_filter($attendances, fn($a) => $a['status'] === 'absent'));

        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'late_days' => $lateDays,
            'absent_days' => $absentDays,
            'total_hours' => $totalHours,
            'total_overtime_hours' => $totalOvertimeHours,
            'average_hours_per_day' => $totalDays > 0 ? round($totalHours / $totalDays, 2) : 0,
        ];
    }

    public function getAllAttendanceByDate(string $date): array
    {
        return $this->attendanceRepository->getAllAttendanceByDate($date);
    }

    /**
     * Get all attendance records with filters.
     */
    public function getAllAttendances(array $filters = []): array
    {
        return $this->attendanceRepository->getAllAttendances($filters);
    }

    /**
     * Get attendance statistics.
     */
    public function getAttendanceStats(): array
    {
        return $this->attendanceRepository->getAttendanceStats();
    }

    /**
     * Create new attendance record.
     */
    public function createAttendance(array $data): Attendance
    {
        // Validate required fields
        if (!isset($data['employee_id']) || !isset($data['date'])) {
            throw new \Exception('Employee ID and date are required');
        }

        // Check if attendance already exists for this employee on this date
        $existingAttendance = $this->attendanceRepository->getAttendanceByEmployeeAndDate(
            $data['employee_id'], 
            $data['date']
        );
        
        if ($existingAttendance) {
            throw new \Exception('Attendance record already exists for this employee on this date');
        }

        return $this->attendanceRepository->create($data);
    }
}