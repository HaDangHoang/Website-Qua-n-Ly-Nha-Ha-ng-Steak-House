<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'John Doe',
            'phone' => '123456789',
            'address' => '123 Main St',
            'email' => 'john.doe@example.com',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'status' => 'active',
            'hire_date' => '2020-01-01',
            'position' => 'Manager',
            'role_id' => 1,
            'avatar' => 'john_avatar.jpg',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'phone' => '987654321',
            'address' => '456 Main St',
            'email' => 'jane.smith@example.com',
            'gender' => 'female',
            'date_of_birth' => '1995-05-05',
            'status' => 'inactive',
            'hire_date' => '2021-05-01',
            'position' => 'Assistant',
            'role_id' => 2,
            'avatar' => 'jane_avatar.jpg',
            'password' => Hash::make('password'),
        ]);

        
    }
}
