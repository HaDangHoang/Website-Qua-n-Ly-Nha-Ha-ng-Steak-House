<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationHistory;

class ReservationHistorySeeder extends Seeder
{
    public function run()
    {
        ReservationHistory::create([
            'reservation_id' => 1,
            'change_time' => now(),
            'status' => 'confirmed',
            'note' => 'Reservation confirmed successfully.',
        ]);

        ReservationHistory::create([
            'reservation_id' => 2,
            'change_time' => now(),
            'status' => 'cancelled',
            'note' => 'Reservation cancelled by customer.',
        ]);
    }
}
