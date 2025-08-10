<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(5)->get();
        
        // Tạo notifications với cấu trúc mới
        foreach ($users as $user) {
            // Notification chào mừng
            Notification::create([
                'type' => 'in_app',
                'title' => 'Chào mừng đến với Team Hub',
                'message' => 'Chào mừng bạn đến với hệ thống quản lý team của chúng tôi.',
                'data' => json_encode([
                    'welcome_message' => true,
                    'user_id' => $user->id
                ]),
                'status' => 'sent',
                'priority' => 'normal',
                'sent_at' => now(),
                'recipients' => json_encode([$user->id]),
                'channel' => 'in_app',
                'is_read' => false,
                'category' => 'system',
                'action_url' => '/dashboard',
                'metadata' => json_encode([
                    'icon' => 'welcome',
                    'color' => 'blue'
                ]),
            ]);
            
            // Notification cập nhật profile
            Notification::create([
                'type' => 'in_app',
                'title' => 'Hồ sơ đã được cập nhật',
                'message' => 'Hồ sơ của bạn đã được cập nhật thành công.',
                'data' => json_encode([
                    'profile_updated' => true,
                    'updated_fields' => ['avatar', 'bio']
                ]),
                'status' => 'sent',
                'priority' => 'normal',
                'sent_at' => now()->subHours(2),
                'recipients' => json_encode([$user->id]),
                'channel' => 'in_app',
                'is_read' => true,
                'category' => 'system',
                'action_url' => '/profile',
                'metadata' => json_encode([
                    'icon' => 'profile',
                    'color' => 'green'
                ]),
            ]);
            
            // Notification hợp đồng chờ ký
            Notification::create([
                'type' => 'in_app',
                'title' => 'Hợp đồng chờ ký',
                'message' => 'Bạn có một hợp đồng đang chờ chữ ký của bạn.',
                'data' => json_encode([
                    'contract_id' => rand(1, 100),
                    'contract_type' => 'employment'
                ]),
                'status' => 'sent',
                'priority' => 'high',
                'sent_at' => now()->subDays(1),
                'recipients' => json_encode([$user->id]),
                'channel' => 'in_app',
                'is_read' => false,
                'category' => 'contract',
                'action_url' => '/contracts/pending',
                'metadata' => json_encode([
                    'icon' => 'contract',
                    'color' => 'orange'
                ]),
            ]);
            
            // Notification dự án mới
            Notification::create([
                'type' => 'in_app',
                'title' => 'Dự án mới được giao',
                'message' => 'Bạn đã được giao dự án "Website Redesign" mới.',
                'data' => json_encode([
                    'project_id' => rand(1, 50),
                    'project_name' => 'Website Redesign',
                    'role' => 'developer'
                ]),
                'status' => 'sent',
                'priority' => 'normal',
                'sent_at' => now()->subDays(2),
                'recipients' => json_encode([$user->id]),
                'channel' => 'in_app',
                'is_read' => false,
                'category' => 'project',
                'action_url' => '/projects',
                'metadata' => json_encode([
                    'icon' => 'project',
                    'color' => 'purple'
                ]),
            ]);
            
            // Notification email
            Notification::create([
                'type' => 'email',
                'title' => 'Báo cáo hàng tuần',
                'message' => 'Báo cáo hoạt động hàng tuần của bạn đã được gửi qua email.',
                'data' => json_encode([
                    'report_type' => 'weekly',
                    'report_period' => '2024-01-01 to 2024-01-07'
                ]),
                'status' => 'sent',
                'priority' => 'low',
                'sent_at' => now()->subDays(3),
                'recipients' => json_encode([$user->id]),
                'channel' => 'email',
                'is_read' => true,
                'category' => 'system',
                'action_url' => null,
                'metadata' => json_encode([
                    'icon' => 'email',
                    'color' => 'blue'
                ]),
            ]);
        }
    }
} 