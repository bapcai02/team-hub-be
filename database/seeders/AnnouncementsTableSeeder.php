<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $visibleTo = ['all', 'department', 'role'];
        for ($i = 1; $i <= 10; $i++) {
            Announcement::create([
                'title' => 'Thông báo ' . $i,
                'content' => 'Nội dung thông báo ' . $i,
                'visible_to' => $visibleTo[array_rand($visibleTo)],
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->addDays(rand(1, 30)),
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 