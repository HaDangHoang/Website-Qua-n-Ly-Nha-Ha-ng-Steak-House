<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // $this->call([
        //     RoleSeeder::class,
        //     PermissionSeeder::class,
        //     CouponSeeder::class,
        //     ReservationSeeder::class,
        //     OrderSeeder::class,
        //     FeedbackSeeder::class,
        //     TableSeeder::class,
        //     IngredientTypeSeeder::class,
        //     IngredientSeeder::class,
        //     SupplierSeeder::class,
        //     ReservationDishesSeeder::class,
        //     ReservationTableSeeder::class,
        //     PaymentSeeder::class,
        //     ReservationHistorySeeder::class,
        // ]);


        // Gọi các Seeder để seed quyền và tài khoản supperadmin
        $this->call([
            // PermissionsSeeder::class,
            SuperAdminSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

    }

}
