<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['name' => 'Xem bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:07:48', 'updated_at' => '2024-09-20 04:07:48'],
            ['name' => 'Tạo mới bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:08:12', 'updated_at' => '2024-09-20 04:08:12'],
            ['name' => 'Sửa bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:08:20', 'updated_at' => '2024-09-20 04:08:20'],
            ['name' => 'Xóa bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:08:33', 'updated_at' => '2024-09-20 04:08:33'],
            ['name' => 'Xem đặt bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:08:59', 'updated_at' => '2024-09-20 04:08:59'],
            ['name' => 'Tạo mới đặt bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:08', 'updated_at' => '2024-09-20 04:09:08'],
            ['name' => 'Sửa đặt bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:19', 'updated_at' => '2024-09-20 04:09:19'],
            ['name' => 'Xóa đặt bàn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:27', 'updated_at' => '2024-09-20 04:09:27'],
            ['name' => 'Xem món ăn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:35', 'updated_at' => '2024-09-20 04:09:35'],
            ['name' => 'Tạo mới món ăn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:46', 'updated_at' => '2024-09-20 04:09:46'],
            ['name' => 'Sửa món ăn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:09:52', 'updated_at' => '2024-09-20 04:09:52'],
            ['name' => 'Xóa món ăn', 'guard_name' => 'web', 'created_at' => '2024-09-20 04:10:04', 'updated_at' => '2024-09-20 04:10:04'],
            ['name' => 'Xem combo', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:08:47', 'updated_at' => '2024-09-20 07:08:47'],
            ['name' => 'Tạo mới combo', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:08:55', 'updated_at' => '2024-09-20 07:08:55'],
            ['name' => 'Sửa combo', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:09:01', 'updated_at' => '2024-09-20 07:09:01'],
            ['name' => 'Xóa combo', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:09:08', 'updated_at' => '2024-09-20 07:09:08'],
            ['name' => 'Xem quyền hạn', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:11:15', 'updated_at' => '2024-09-20 07:11:15'],
            ['name' => 'Tạo mới quyền hạn', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:11:24', 'updated_at' => '2024-09-20 07:11:24'],
            ['name' => 'Sửa quyền hạn', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:11:36', 'updated_at' => '2024-09-20 07:11:36'],
            ['name' => 'Xóa quyền hạn', 'guard_name' => 'web', 'created_at' => '2024-09-20 07:11:57', 'updated_at' => '2024-09-20 07:11:57']
        ]);
    }
}
