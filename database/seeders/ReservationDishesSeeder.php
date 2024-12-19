<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationDish;
use App\Models\ReservationDishes;

class ReservationDishesSeeder extends Seeder
{
    public function run()
    {
        ReservationDishes::create([
            'reservation_id' => 1,
            'dish_id' => 1,
            'quantity' => 2,
            'price' => 50.00,
        ]);

        ReservationDishes::create([
            'reservation_id' => 2,
            'dish_id' => 2,
            'quantity' => 3,
            'price' => 75.00,
        ]);
    }
}
