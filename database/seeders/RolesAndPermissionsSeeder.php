<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Danh sách quyền hạn
        $permissions = [
            'Xem danh mục',
            'Tạo mới danh mục',
            'Sửa danh mục',
            'Xóa danh mục',
            'Xem combo',
            'Tạo mới combo',
            'Sửa combo',
            'Xem mã giảm giá',
            'Tạo mới mã giảm giá',
            'Sửa mã giảm giá',
            'Xóa mã giảm giá',
            'Xem món ăn',
            'Tạo mới món ăn',
            'Sửa món ăn',
            'Xóa món ăn',
            'Xem feedback',
            'Tạo mới feedback',
            'Sửa feedback',
            'Xóa feedback',
            'Xem nguyên liệu',
            'Tạo mới nguyên liệu',
            'Sửa nguyên liệu',
            'xóa nguyên liệu',
            'Xem nhập kho',
            'Tạo mới nhập kho',
            'Sửa nhập kho',
            'Xóa nhập kho',
            'Xem order',
            'Tạo mới order',
            'Sửa order',
            'Xóa order',
            'Xem thanh toán',
            'Tạo mới thanh toán',
            'Sửa thanh toán',
            'Xóa thanh toán',
            'Xem quyền hạn',
            'Tạo mới quyền hạn',
            'Sửa quyền hạn',
            'Xóa quyền hạn',
            'Xem đặt bàn',
            'Tạo mới đặt bàn',
            'Sửa đặt bàn',
            'Xóa đặt bàn',
            'Xem lịch sử đặt bàn',
            'Tạo mới lịch sử đặt bàn',
            'Sửa lịch sử đặt bàn',
            'Xóa lịch đặt bàn',
            'Xem vai trò',
            'Tạo mới vai trò',
            'Sửa vai trò',
            'Xóa vai trò',
            'Xem nhà cung cấp',
            'Tạo mới nhà cung cấp',
            'Sửa nhà cung cấp',
            'Xóa nhà cung cấp',
            'Xem bàn',
            'Tạo mới bàn',
            'Sửa bàn',
            'Xóa bàn',
            'Xem người dùng',
            'Tạo mới người dùng',
            'Sửa người dùng',
            'Xóa người dùng',
            'Xem hoàn tiền',
            'Tạo mới hoàn tiền',
            'Sửa hoàn tiền',
            'Xóa hoàn tiền',
            'Xem tồn kho',
            'Tạo mới tồn kho',
            'Sửa tồn kho',
            'Xóa tồn kho',
            'Xem thống kê',
            'access pos',
            'Xem bếp',
            'Xem thống kê'

        ];

        
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web'] 
            );
        }

        echo "Tất cả quyền hạn đã được tạo thành công!\n";
    }
}
