<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IngredientType;

class IngredientTypeSeeder extends Seeder
{
    public function run()
    {
        IngredientType::create([
            'name' => 'Vegetable',
        ]);

        IngredientType::create([
            'name' => 'Meat',
        ]);

        IngredientType::create([
            'name' => 'Dairy',
        ]);
    }
}
