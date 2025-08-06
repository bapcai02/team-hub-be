<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employees
        $employees = Employee::all();
        
        if ($employees->isEmpty()) {
            $this->command->info('No employees found. Please run EmployeesTableSeeder first.');
            return;
        }

        // Generate attendance data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($employees as $employee) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends (Saturday = 6, Sunday = 0)
                if ($currentDate->dayOfWeek !== 0 && $currentDate->dayOfWeek !== 6) {
                    $this->createAttendanceRecord($employee, $currentDate);
                }
                
                $currentDate->addDay();
            }
        }

        $this->command->info('Attendance data seeded successfully!');
    }

    private function createAttendanceRecord($employee, $date)
    {
        // Random attendance status
        $statuses = ['present', 'present', 'present', 'late', 'absent'];
        $status = $statuses[array_rand($statuses)];

        $attendanceData = [
            'employee_id' => $employee->id,
            'date' => $date->format('Y-m-d'),
            'status' => $status,
            'notes' => null,
            'location' => 'Office',
            'ip_address' => '192.168.1.' . rand(100, 200),
        ];

        // Add time data for present/late status
        if (in_array($status, ['present', 'late'])) {
            $checkInHour = $status === 'late' ? rand(8, 10) : rand(7, 9);
            $checkInMinute = rand(0, 59);
            
            $checkOutHour = rand(17, 19);
            $checkOutMinute = rand(0, 59);

            $attendanceData['check_in_time'] = $date->copy()->setTime($checkInHour, $checkInMinute);
            $attendanceData['check_out_time'] = $date->copy()->setTime($checkOutHour, $checkOutMinute);
            
            // Calculate total hours
            $checkIn = Carbon::parse($attendanceData['check_in_time']);
            $checkOut = Carbon::parse($attendanceData['check_out_time']);
            $totalHours = $checkOut->diffInHours($checkIn, true);
            
            // Add break time
            $breakStartHour = rand(12, 13);
            $breakStartMinute = rand(0, 30);
            $breakEndHour = $breakStartHour + 1;
            $breakEndMinute = rand(0, 30);
            
            $attendanceData['break_start_time'] = $date->copy()->setTime($breakStartHour, $breakStartMinute);
            $attendanceData['break_end_time'] = $date->copy()->setTime($breakEndHour, $breakEndMinute);
            
            // Calculate actual working hours (subtract break time)
            $breakStart = Carbon::parse($attendanceData['break_start_time']);
            $breakEnd = Carbon::parse($attendanceData['break_end_time']);
            $breakHours = $breakEnd->diffInHours($breakStart, true);
            $actualHours = $totalHours - $breakHours;
            
            $attendanceData['total_hours'] = round($actualHours, 2);
            $attendanceData['overtime_hours'] = $actualHours > 8 ? round($actualHours - 8, 2) : 0;
        }

        // Add some random notes
        if (rand(1, 10) === 1) {
            $notes = [
                'Worked from home',
                'Team meeting',
                'Client presentation',
                'Training session',
                'Project deadline',
            ];
            $attendanceData['notes'] = $notes[array_rand($notes)];
        }

        Attendance::create($attendanceData);
    }
} 