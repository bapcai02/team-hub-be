<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        // Get all users and roles
        $users = User::all();
        $roles = Role::all();

        if ($users->isEmpty() || $roles->isEmpty()) {
            $this->command->info('No users or roles found. Skipping role assignment.');
            return;
        }

        // Clear existing role assignments
        DB::table('role_user')->truncate();

        // Assign roles to users
        $roleAssignments = [];

        foreach ($users as $index => $user) {
            // Assign different roles based on user index
            $roleId = $this->getRoleIdForUser($index, $roles);
            
            $roleAssignments[] = [
                'user_id' => $user->id,
                'role_id' => $roleId,
            ];
        }

        // Insert role assignments
        DB::table('role_user')->insert($roleAssignments);

        $this->command->info('Role assignments completed successfully.');
    }

    private function getRoleIdForUser($userIndex, $roles)
    {
        $roleNames = [
            'Super Admin',
            'Admin', 
            'Manager',
            'Team Lead',
            'Developer',
            'HR Manager',
            'Finance Manager',
            'User'
        ];

        $roleName = $roleNames[$userIndex % count($roleNames)];
        $role = $roles->where('name', $roleName)->first();
        
        return $role ? $role->id : $roles->first()->id;
    }
} 