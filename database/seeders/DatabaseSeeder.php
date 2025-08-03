<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentsTableSeeder::class,
            EmployeesTableSeeder::class,
            LeavesTableSeeder::class,
            AttendancesTableSeeder::class,
            EmployeeEvaluationsTableSeeder::class,
            DeviceCategorySeeder::class,
            DeviceSeeder::class,
        ]);
    }
}
