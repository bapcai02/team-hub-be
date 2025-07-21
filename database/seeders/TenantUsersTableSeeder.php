<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TenantUser;
use App\Models\Tenant;
use App\Models\User;

class TenantUsersTableSeeder extends Seeder
{
    public function run()
    {
        $tenantIds = Tenant::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        if (empty($tenantIds) || empty($userIds)) return;
        for ($i = 1; $i <= 10; $i++) {
            TenantUser::create([
                'tenant_id' => $tenantIds[array_rand($tenantIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'role' => 'member',
            ]);
        }
    }
} 