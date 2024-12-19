<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Combo;

class ComboSeeder extends Seeder
{
    public function run()
    {
        Combo::create([
            'name' => 'Lunch Special Combo',
            'description' => 'Includes a main dish, dessert, and drink.',
            'price' => 12.99,
            'image' => 'lunch_combo.jpg',
            'quantity_dishes' => 3,
        ]);

        Combo::create([
            'name' => 'Family Dinner Combo',
            'description' => 'Perfect for a family of 4. Includes 4 dishes and 2 sides.',
            'price' => 49.99,
            'image' => 'family_combo.jpg',
            'quantity_dishes' => 6,
        ]);
    }
}
