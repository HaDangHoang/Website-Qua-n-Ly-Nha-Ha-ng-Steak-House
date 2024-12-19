<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'role_name' => 'Admin',
            'description' => 'Has full access to all system features.',
        ]);

        Role::create([
            'role_name' => 'Manager',
            'description' => 'Manages resources and oversees daily operations.',
        ]);

        Role::create([
            'role_name' => 'User',
            'description' => 'Can access basic features of the system.',
        ]);
    }
}
