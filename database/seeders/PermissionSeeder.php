<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create([
            'permission_name' => 'Create User',
            'description' => 'Allows user to create other users',
        ]);

        Permission::create([
            'permission_name' => 'Edit User',
            'description' => 'Allows user to edit other users',
        ]);

        Permission::create([
            'permission_name' => 'Delete User',
            'description' => 'Allows user to delete other users',
        ]);
    }
}
