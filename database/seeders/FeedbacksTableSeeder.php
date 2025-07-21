<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;

class FeedbacksTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $types = ['bug', 'feature_request', 'general'];
        $statuses = ['new', 'in_progress', 'resolved'];
        for ($i = 1; $i <= 10; $i++) {
            Feedback::create([
                'user_id' => $userIds[array_rand($userIds)],
                'type' => $types[array_rand($types)],
                'content' => 'Nội dung phản hồi ' . $i,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
} 