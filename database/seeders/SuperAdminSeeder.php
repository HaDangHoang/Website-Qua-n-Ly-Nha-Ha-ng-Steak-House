<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Tạo vai trò admin nếu chưa có
        $role = Role::firstOrCreate(['name' => 'SupperAdmin']);

        // Tạo tài khoản supperadmin
        $superAdmin = User::firstOrCreate(
            ['email' => 'supperadmin@gmail.com'], // Email supperadmin cố định
            [
                'name' => 'Supper Admin',
                'password' => Hash::make('password'), // Mật khẩu supperadmin mặc định là 'password'
                'status' => 'active',
            ]
        );

        // Gán vai trò admin cho tài khoản supperadmin
        $superAdmin->assignRole($role);

        // Gán tất cả quyền cho vai trò admin
        $permissions = Permission::all(); // Lấy tất cả các quyền trong bảng permissions
        $role->syncPermissions($permissions);
    }
}
