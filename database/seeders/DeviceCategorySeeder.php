<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceCategory;

class DeviceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'code' => 'CAT001',
                'slug' => 'laptops',
                'description' => 'Portable computers for work and development',
                'icon' => 'laptop',
                'is_active' => true,
            ],
            [
                'name' => 'Desktops',
                'code' => 'CAT002',
                'slug' => 'desktops',
                'description' => 'Desktop computers for office work',
                'icon' => 'desktop',
                'is_active' => true,
            ],
            [
                'name' => 'Tablets',
                'code' => 'CAT003',
                'slug' => 'tablets',
                'description' => 'Tablet devices for design and presentations',
                'icon' => 'tablet',
                'is_active' => true,
            ],
            [
                'name' => 'Phones',
                'code' => 'CAT004',
                'slug' => 'phones',
                'description' => 'Mobile phones for communication',
                'icon' => 'phone',
                'is_active' => true,
            ],
            [
                'name' => 'Printers',
                'code' => 'CAT005',
                'slug' => 'printers',
                'description' => 'Printing devices for office use',
                'icon' => 'printer',
                'is_active' => true,
            ],
            [
                'name' => 'Scanners',
                'code' => 'CAT006',
                'slug' => 'scanners',
                'description' => 'Scanning devices for document digitization',
                'icon' => 'scanner',
                'is_active' => true,
            ],
            [
                'name' => 'Network Equipment',
                'code' => 'CAT007',
                'slug' => 'network-equipment',
                'description' => 'Network devices like routers, switches',
                'icon' => 'network',
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'code' => 'CAT008',
                'slug' => 'other',
                'description' => 'Other miscellaneous devices',
                'icon' => 'device',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            DeviceCategory::create($categoryData);
        }
    }
} 