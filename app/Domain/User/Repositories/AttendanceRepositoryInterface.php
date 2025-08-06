<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\Attendance;

interface AttendanceRepositoryInterface
{
    public function create(array $data): Attendance;
    public function find($id): ?Attendance;
    public function update($id, array $data): ?Attendance;
    public function delete($id): bool;
    public function getTodayAttendance(int $employeeId): ?Attendance;
    public function getAttendanceByDateRange(int $employeeId, string $startDate, string $endDate): array;
    public function getAttendanceByDate(string $date): array;
    public function getAttendanceByDateRangeForAll(string $startDate, string $endDate): array;
    public function getAttendanceByMonth(int $employeeId, int $month, int $year): array;
    public function getAllAttendanceByDate(string $date): array;
    
    /**
     * Get all attendance records with filters.
     */
    public function getAllAttendances(array $filters = []): array;
    
    /**
     * Get attendance statistics.
     */
    public function getAttendanceStats(): array;
    
    /**
     * Get attendance by employee and date.
     */
    public function getAttendanceByEmployeeAndDate(int $employeeId, string $date): ?Attendance;
}