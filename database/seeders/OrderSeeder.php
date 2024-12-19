<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::create([
            'reservation_id' => 1,
            'staff_id' => 1,
            'table_id' => 1,
            'customer_id' => 1,
            'total_amount' => 100.00,
            'order_type' => 'dine_in',
            'status' => 'Completed',
            'discount_amount' => 10.00,
            'final_amount' => 90.00,
        ]);

        Order::create([
            'reservation_id' => 2,
            'staff_id' => 2,
            'table_id' => 2,
            'customer_id' => null,
            'total_amount' => 150.00,
            'order_type' => 'delivery',
            'status' => 'Pending',
            'discount_amount' => 0.00,
            'final_amount' => 150.00,
        ]);
    }
}
