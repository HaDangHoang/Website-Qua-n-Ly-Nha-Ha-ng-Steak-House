<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        Reservation::create([
            'customer_id' => 1,
            'coupon_id' => null,
            'reservation_time' => '2024-09-17 18:00:00',
            'reservation_date' => '2024-09-17',
            'guest_count' => 4,
            'deposit_amount' => 50.00,
            'total_amount' => 200.00,
            'remaining_amount' => 150.00,
            'status' => 'Confirmed',
            'note' => 'Special table arrangement required',
        ]);

        Reservation::create([
            'customer_id' => 2,
            'coupon_id' => 1,
            'reservation_time' => '2024-09-18 19:30:00',
            'reservation_date' => '2024-09-18',
            'guest_count' => 2,
            'deposit_amount' => 20.00,
            'total_amount' => 80.00,
            'remaining_amount' => 60.00,
            'status' => 'Pending',
            'note' => 'Vegetarian menu required',
        ]);
    }
}
