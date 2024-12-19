<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('reservation_table')->insert([
            [
                'reservation_id' => 1,
                'status' => 'reserved',
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
