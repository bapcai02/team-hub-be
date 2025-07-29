<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeavesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaves = [
            [
                'employee_id' => 1,
                'type' => 'annual',
                'date_from' => '2024-01-15',
                'date_to' => '2024-01-17',
                'reason' => 'Nghỉ phép năm',
                'status' => 'approved',
                'approved_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 2,
                'type' => 'sick',
                'date_from' => '2024-01-20',
                'date_to' => '2024-01-21',
                'reason' => 'Ốm, cần nghỉ ngơi',
                'status' => 'approved',
                'approved_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 3,
                'type' => 'personal',
                'date_from' => '2024-02-01',
                'date_to' => '2024-02-01',
                'reason' => 'Việc riêng',
                'status' => 'pending',
                'approved_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 4,
                'type' => 'annual',
                'date_from' => '2024-02-10',
                'date_to' => '2024-02-12',
                'reason' => 'Nghỉ phép năm',
                'status' => 'rejected',
                'approved_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 5,
                'type' => 'sick',
                'date_from' => '2024-02-15',
                'date_to' => '2024-02-16',
                'reason' => 'Ốm, cần nghỉ ngơi',
                'status' => 'approved',
                'approved_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('leaves')->insert($leaves);
    }
} 