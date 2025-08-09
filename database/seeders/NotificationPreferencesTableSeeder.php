<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domain\Notification\Entities\NotificationPreference;
use App\Models\User;

class NotificationPreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $categories = [
            NotificationPreference::CATEGORY_SYSTEM,
            NotificationPreference::CATEGORY_PROJECT,
            NotificationPreference::CATEGORY_FINANCE,
            NotificationPreference::CATEGORY_HR,
            NotificationPreference::CATEGORY_DEVICE,
            NotificationPreference::CATEGORY_CONTRACT,
        ];

        foreach ($users as $user) {
            foreach ($categories as $category) {
                NotificationPreference::create([
                    'user_id' => $user->id,
                    'category' => $category,
                    'channels' => [
                        NotificationPreference::CHANNEL_EMAIL,
                        NotificationPreference::CHANNEL_IN_APP,
                        NotificationPreference::CHANNEL_PUSH,
                    ],
                    'frequency' => [
                        'type' => NotificationPreference::FREQUENCY_IMMEDIATE,
                    ],
                    'quiet_hours' => [
                        'start' => '22:00',
                        'end' => '08:00',
                    ],
                    'is_active' => true,
                    'custom_settings' => [
                        'digest_enabled' => false,
                        'urgent_only' => false,
                    ],
                ]);
            }
        }
    }
}
