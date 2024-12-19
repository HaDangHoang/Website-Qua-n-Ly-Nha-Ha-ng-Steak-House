<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\Dishes;

class DishesSeeder extends Seeder
{
    public function run()
    {
        Dishes::create([
            'category_id' => 1,
            'name' => 'Spaghetti',
            'price' => 5.99,
            'quantity' => 100,
            'image' => 'spaghetti.jpg',
            'status' => 'available',
            'description' => 'Delicious spaghetti with tomato sauce and cheese.',
        ]);

        Dishes::create([
            'category_id' => 2,
            'name' => 'Grilled Chicken',
            'price' => 8.99,
            'quantity' => 50,
            'image' => 'grilled_chicken.jpg',
            'status' => 'out_of_stock',
            'description' => 'Juicy grilled chicken with herbs and spices.',
        ]);
    }
}
