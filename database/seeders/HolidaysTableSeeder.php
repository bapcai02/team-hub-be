<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // National holidays 2025
            [
                'name' => 'Tết Dương lịch',
                'date' => '2025-01-01',
                'type' => 'national',
                'description' => 'Ngày đầu năm mới',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tết Nguyên đán',
                'date' => '2025-01-29',
                'type' => 'national',
                'description' => 'Tết cổ truyền Việt Nam',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tết Nguyên đán',
                'date' => '2025-01-30',
                'type' => 'national',
                'description' => 'Tết cổ truyền Việt Nam',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tết Nguyên đán',
                'date' => '2025-01-31',
                'type' => 'national',
                'description' => 'Tết cổ truyền Việt Nam',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Giỗ tổ Hùng Vương',
                'date' => '2025-04-07',
                'type' => 'national',
                'description' => 'Ngày giỗ tổ Hùng Vương',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Giải phóng miền Nam',
                'date' => '2025-04-30',
                'type' => 'national',
                'description' => 'Ngày giải phóng miền Nam, thống nhất đất nước',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Quốc tế Lao động',
                'date' => '2025-05-01',
                'type' => 'national',
                'description' => 'Ngày Quốc tế Lao động',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Quốc khánh',
                'date' => '2025-09-02',
                'type' => 'national',
                'description' => 'Ngày Quốc khánh nước Cộng hòa Xã hội Chủ nghĩa Việt Nam',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tết Trung thu',
                'date' => '2025-10-06',
                'type' => 'national',
                'description' => 'Tết Trung thu',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tết Dương lịch 2026',
                'date' => '2026-01-01',
                'type' => 'national',
                'description' => 'Ngày đầu năm mới',
                'is_paid' => true,
                'is_active' => true,
            ],
            
            // Company holidays
            [
                'name' => 'Ngày thành lập công ty',
                'date' => '2025-03-15',
                'type' => 'company',
                'description' => 'Kỷ niệm ngày thành lập công ty',
                'is_paid' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Ngày Team Building',
                'date' => '2025-06-15',
                'type' => 'company',
                'description' => 'Hoạt động team building hàng năm',
                'is_paid' => true,
                'is_active' => true,
            ],
            
            // Regional holidays
            [
                'name' => 'Lễ hội địa phương',
                'date' => '2025-08-15',
                'type' => 'regional',
                'description' => 'Lễ hội truyền thống địa phương',
                'is_paid' => false,
                'is_active' => true,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
} 