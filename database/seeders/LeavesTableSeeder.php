<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;
use App\Models\Employee;

class LeavesTableSeeder extends Seeder
{
    public function run()
    {
        $employeeIds = Employee::pluck('id')->all();
        $types = ['paid', 'unpaid', 'sick', 'remote', 'comp-off'];
        $statuses = ['pending', 'approved', 'rejected'];
        for ($i = 1; $i <= 10; $i++) {
            Leave::create([
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'type' => $types[array_rand($types)],
                'date_from' => now()->subDays(rand(1, 30)),
                'date_to' => now()->addDays(rand(1, 10)),
                'reason' => 'Nghỉ phép mẫu ' . $i,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
} 