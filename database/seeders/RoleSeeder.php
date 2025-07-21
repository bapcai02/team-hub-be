<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'manager'],
            ['id' => 3, 'name' => 'user'],
        ];
        DB::table('roles')->insert($roles);

        $permissions = [
            ['id' => 1, 'name' => 'view_users'],
            ['id' => 2, 'name' => 'edit_users'],
            ['id' => 3, 'name' => 'delete_users'],
            ['id' => 4, 'name' => 'view_roles'],
            ['id' => 5, 'name' => 'edit_roles'],
        ];
        DB::table('permissions')->insert($permissions);

        $permissionRole = [
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 1, 'role_id' => 3],
        ];
        DB::table('permission_role')->insert($permissionRole);

        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 1,
        ]);
    }
} 