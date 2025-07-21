<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'manager', 'user'];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }
    }
} 