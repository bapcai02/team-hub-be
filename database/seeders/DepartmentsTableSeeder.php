<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        $names = ['Phòng Kỹ thuật', 'Phòng Kinh doanh', 'Phòng Nhân sự', 'Phòng Kế toán', 'Phòng Marketing'];
        foreach ($names as $name) {
            Department::create([
                'name' => $name,
            ]);
        }
    }
} 