<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'reservation_id',
        'bill_id',
        'transaction_id',
        'transaction_amount',
        'refund_amount',
        'payment_method',
        'status',
        'transaction_status',
    ];

    protected $dates = ['deleted_at'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
