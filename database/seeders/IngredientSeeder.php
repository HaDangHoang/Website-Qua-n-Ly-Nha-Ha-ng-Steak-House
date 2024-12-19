<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        Ingredient::create([
            'name' => 'Tomato',
            'supplier_id' => 1,
            'price' => 20.50,
            'unit' => 'kg',
            'ingredient_type_id' => 1, // Giả sử loại 'Vegetable' có id là 1
        ]);

        Ingredient::create([
            'name' => 'Chicken',
            'supplier_id' => 2,
            'price' => 50.00,
            'unit' => 'kg',
            'ingredient_type_id' => 2, // Giả sử loại 'Meat' có id là 2
        ]);
    }
}
