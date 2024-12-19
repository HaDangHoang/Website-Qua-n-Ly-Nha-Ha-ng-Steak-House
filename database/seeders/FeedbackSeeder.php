<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        Feedback::create([
            'reservation_id' => 1,
            'customer_id' => 1,
            'content' => 'The service was excellent!',
            'rating' => 5,
        ]);

        Feedback::create([
            'reservation_id' => 2,
            'customer_id' => 2,
            'content' => 'Food was good but service can be improved.',
            'rating' => 4,
        ]);
    }
}
