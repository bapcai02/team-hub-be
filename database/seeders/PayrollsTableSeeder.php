<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Models\Employee;

class PayrollsTableSeeder extends Seeder
{
    public function run()
    {
        $employeeIds = Employee::pluck('id')->all();
        $statuses = ['pending', 'approved', 'paid'];
        for ($i = 1; $i <= 10; $i++) {
            $base = rand(8, 30) * 1000000;
            $allowance = rand(0, 5) * 1000000;
            $deduction = rand(0, 3) * 500000;
            $net = $base + $allowance - $deduction;
            Payroll::create([
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'month' => now()->subMonths(rand(0, 12)),
                'base_salary' => $base,
                'allowance' => $allowance,
                'deduction' => $deduction,
                'net_salary' => $net,
                'status' => $statuses[array_rand($statuses)],
                'generated_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
} 