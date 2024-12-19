<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run()
    {
        Table::create([
            'area' => 'Tầng 1',
            'table_number' => 1,
            'table_type' => 'VIP',
            'status' => 'Available',
            'parent_id' => null,
        ]);

        Table::create([
            'area' => 'Tầng 2',
            'table_number' => 2,
            'table_type' => 'Thường',
            'status' => 'Reserved',
            'parent_id' => 1,
        ]);
    }
}
