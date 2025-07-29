<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendances = [
            [
                'employee_id' => 1,
                'date' => '2024-01-15',
                'check_in_time' => '2024-01-15 08:00:00',
                'check_out_time' => '2024-01-15 17:00:00',
                'break_start_time' => '2024-01-15 12:00:00',
                'break_end_time' => '2024-01-15 13:00:00',
                'total_hours' => 8.0,
                'overtime_hours' => 0.0,
                'status' => 'present',
                'note' => 'Làm việc bình thường',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 2,
                'date' => '2024-01-15',
                'check_in_time' => '2024-01-15 08:30:00',
                'check_out_time' => '2024-01-15 17:30:00',
                'break_start_time' => '2024-01-15 12:00:00',
                'break_end_time' => '2024-01-15 13:00:00',
                'total_hours' => 8.0,
                'overtime_hours' => 0.5,
                'status' => 'late',
                'note' => 'Đi muộn 30 phút',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 3,
                'date' => '2024-01-15',
                'check_in_time' => '2024-01-15 08:00:00',
                'check_out_time' => '2024-01-15 13:00:00',
                'break_start_time' => null,
                'break_end_time' => null,
                'total_hours' => 5.0,
                'overtime_hours' => 0.0,
                'status' => 'half_day',
                'note' => 'Nghỉ nửa ngày',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 4,
                'date' => '2024-01-15',
                'check_in_time' => null,
                'check_out_time' => null,
                'break_start_time' => null,
                'break_end_time' => null,
                'total_hours' => 0.0,
                'overtime_hours' => 0.0,
                'status' => 'absent',
                'note' => 'Nghỉ phép',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 5,
                'date' => '2024-01-15',
                'check_in_time' => '2024-01-15 08:00:00',
                'check_out_time' => '2024-01-15 18:00:00',
                'break_start_time' => '2024-01-15 12:00:00',
                'break_end_time' => '2024-01-15 13:00:00',
                'total_hours' => 9.0,
                'overtime_hours' => 1.0,
                'status' => 'present',
                'note' => 'Làm thêm giờ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('attendances')->insert($attendances);
    }
} 