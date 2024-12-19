<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'code' => 'DISCOUNT10',
            'description' => 'Get 10% off on your next order',
            'max_uses' => 100,
            'start_time' => '2024-09-17 00:00:00',
            'end_time' => '2024-12-31 23:59:59',
            'discount_type' => 'Percentage',
            'discount_amount' => 10.00,
            'status' => 'active',
        ]);

        Coupon::create([
            'code' => 'FIXED50',
            'description' => 'Get $50 off on your next order',
            'max_uses' => 50,
            'start_time' => '2024-09-17 00:00:00',
            'end_time' => '2024-12-31 23:59:59',
            'discount_type' => 'Fixed',
            'discount_amount' => 50.00,
            'status' => 'active',
        ]);
    }
}
