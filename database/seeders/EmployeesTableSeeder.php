<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'user_id' => 1,
                'department_id' => 1,
                'position' => 'Giám đốc',
                'salary' => 50000000,
                'contract_type' => 'full-time',
                'hire_date' => '2020-01-15',
                'dob' => '1990-05-15',
                'gender' => 'male',
                'phone' => '0901234567',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'department_id' => 2,
                'position' => 'Trưởng phòng',
                'salary' => 35000000,
                'contract_type' => 'full-time',
                'hire_date' => '2020-03-01',
                'dob' => '1988-08-20',
                'gender' => 'female',
                'phone' => '0901234569',
                'address' => '456 Đường XYZ, Quận 2, TP.HCM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'department_id' => 3,
                'position' => 'Nhân viên',
                'salary' => 25000000,
                'contract_type' => 'full-time',
                'hire_date' => '2021-06-15',
                'dob' => '1992-12-10',
                'gender' => 'male',
                'phone' => '0901234571',
                'address' => '789 Đường DEF, Quận 3, TP.HCM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'department_id' => 4,
                'position' => 'Nhân viên',
                'salary' => 22000000,
                'contract_type' => 'full-time',
                'hire_date' => '2021-09-01',
                'dob' => '1995-03-25',
                'gender' => 'female',
                'phone' => '0901234573',
                'address' => '321 Đường GHI, Quận 4, TP.HCM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 5,
                'department_id' => 5,
                'position' => 'Nhân viên',
                'salary' => 20000000,
                'contract_type' => 'full-time',
                'hire_date' => '2022-01-10',
                'dob' => '1993-07-08',
                'gender' => 'male',
                'phone' => '0901234575',
                'address' => '654 Đường JKL, Quận 5, TP.HCM',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('employees')->insert($employees);
    }
} 