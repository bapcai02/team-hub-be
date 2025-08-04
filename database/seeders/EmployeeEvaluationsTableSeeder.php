<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeEvaluationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách employee IDs
        $employeeIds = DB::table('employees')->pluck('id')->toArray();
        
        if (empty($employeeIds)) {
            $this->command->info('No employees found. Skipping employee evaluations seeding.');
            return;
        }

        // Lấy danh sách user IDs để làm evaluator
        $evaluatorIds = DB::table('users')->pluck('id')->toArray();
        
        if (empty($evaluatorIds)) {
            $this->command->info('No users found. Skipping employee evaluations seeding.');
            return;
        }

        $evaluations = [];
        $periods = ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024'];

        foreach ($employeeIds as $employeeId) {
            foreach ($periods as $period) {
                $evaluations[] = [
                    'employee_id' => $employeeId,
                    'evaluator_id' => $evaluatorIds[array_rand($evaluatorIds)],
                    'period' => $period,
                    'score' => rand(35, 50) / 10, // Random score between 3.5 and 5.0
                    'feedback' => 'Nhân viên có hiệu suất tốt trong kỳ này.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('employee_evaluations')->insert($evaluations);
        
        $this->command->info('Employee evaluations seeded successfully!');
    }
} 