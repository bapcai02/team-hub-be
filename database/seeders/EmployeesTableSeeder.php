<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $departmentIds = Department::pluck('id')->all();
        $contractTypes = ['full-time', 'part-time', 'intern'];
        $genders = ['male', 'female', 'other'];
        for ($i = 1; $i <= 10; $i++) {
            Employee::create([
                'user_id' => $userIds[array_rand($userIds)],
                'department_id' => $departmentIds[array_rand($departmentIds)],
                'position' => 'Nhân viên',
                'salary' => rand(8, 30) * 1000000,
                'contract_type' => $contractTypes[array_rand($contractTypes)],
                'hire_date' => now()->subYears(rand(1, 5)),
                'dob' => now()->subYears(rand(22, 40))->subDays(rand(0, 365)),
                'gender' => $genders[array_rand($genders)],
                'phone' => '09' . rand(10000000, 99999999),
                'address' => 'Địa chỉ ' . $i,
            ]);
        }
    }
} 