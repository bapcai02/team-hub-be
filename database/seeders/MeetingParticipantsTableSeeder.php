<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingParticipant;
use App\Models\Meeting;
use App\Models\User;

class MeetingParticipantsTableSeeder extends Seeder
{
    public function run()
    {
        $meetingIds = Meeting::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        if (empty($meetingIds) || empty($userIds)) return;
        for ($i = 1; $i <= 10; $i++) {
            MeetingParticipant::create([
                'meeting_id' => $meetingIds[array_rand($meetingIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'role' => 'member',
            ]);
        }
    }
} 