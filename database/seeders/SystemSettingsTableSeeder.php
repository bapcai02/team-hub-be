<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Team Hub'],
            ['key' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh'],
            ['key' => 'language', 'value' => 'vi'],
            ['key' => 'theme', 'value' => 'light'],
            ['key' => 'maintenance', 'value' => 'off'],
        ];
        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
} 