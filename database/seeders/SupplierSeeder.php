<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        Supplier::create([
            'name' => 'ABC Suppliers',
            'phone' => '123456789',
            'email' => 'contact@abcsuppliers.com',
            'address' => '123 Main Street, City, Country',
        ]);

        Supplier::create([
            'name' => 'XYZ Suppliers',
            'phone' => '987654321',
            'email' => 'info@xyzsuppliers.com',
            'address' => '456 Elm Street, Town, Country',
        ]);
    }
}
