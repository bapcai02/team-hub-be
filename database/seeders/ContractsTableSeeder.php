<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Employee;

class ContractsTableSeeder extends Seeder
{
    public function run()
    {
        $employeeIds = Employee::pluck('id')->all();
        $contractTypes = ['probation', 'fixed-term', 'permanent'];
        $statuses = ['active', 'expired', 'terminated'];
        for ($i = 1; $i <= 10; $i++) {
            Contract::create([
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'contract_type' => $contractTypes[array_rand($contractTypes)],
                'start_date' => now()->subYears(rand(1, 5)),
                'end_date' => now()->addYears(rand(1, 3)),
                'salary' => rand(8, 30) * 1000000,
                'status' => $statuses[array_rand($statuses)],
                'notes' => 'Hợp đồng mẫu ' . $i,
            ]);
        }
    }
} 