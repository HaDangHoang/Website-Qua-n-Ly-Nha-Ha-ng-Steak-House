<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create([
            'reservation_id' => 1,
            'bill_id' => 'BILL-1001',
            'transaction_id' => 12345,
            'transaction_amount' => 100.00,
            'refund_amount' => 0.00,
            'payment_method' => 'Credit Card',
            'status' => 'Completed',
            'transaction_status' => 'completed',
        ]);

        Payment::create([
            'reservation_id' => 2,
            'bill_id' => 'BILL-1002',
            'transaction_id' => 12346,
            'transaction_amount' => 50.00,
            'refund_amount' => 5.00,
            'payment_method' => 'Online',
            'status' => 'Pending',
            'transaction_status' => 'pending',
        ]);
    }
}
