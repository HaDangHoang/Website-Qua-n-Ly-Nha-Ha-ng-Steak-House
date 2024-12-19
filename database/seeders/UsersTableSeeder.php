<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'locnguyen',
                'phone' => '01231232131',
                'address' => NULL,
                'email' => 'admin@gmail.com',
                'gender' => NULL,
                'date_of_birth' => NULL,
                'status' => 'active',
                'hire_date' => NULL,
                'position' => NULL,
                'role_id' => NULL,
                'avatar' => NULL,
                'password' => Hash::make('password'),
                'created_at' => '2024-09-19 09:03:10',
                'updated_at' => '2024-09-20 02:48:55',
                'deleted_at' => NULL
            ],
            [
                'name' => 'SupperAdmin',
                'phone' => '01231232131',
                'address' => NULL,
                'email' => 'supperadmin@gmail.com',
                'gender' => NULL,
                'date_of_birth' => NULL,
                'status' => 'active',
                'hire_date' => NULL,
                'position' => NULL,
                'role_id' => NULL,
                'avatar' => NULL,
                'password' => Hash::make('password'),
                'created_at' => '2024-09-20 06:09:40',
                'updated_at' => '2024-09-20 06:17:40',
                'deleted_at' => NULL
            ],
            [
                'name' => 'Staff',
                'phone' => NULL,
                'address' => NULL,
                'email' => 'staff@gmail.com',
                'gender' => NULL,
                'date_of_birth' => NULL,
                'status' => 'active',
                'hire_date' => NULL,
                'position' => NULL,
                'role_id' => NULL,
                'avatar' => NULL,
                'password' => Hash::make('password'),
                'created_at' => '2024-09-20 06:10:43',
                'updated_at' => '2024-09-20 06:10:43',
                'deleted_at' => NULL
            ]
        ]);
    }
}
