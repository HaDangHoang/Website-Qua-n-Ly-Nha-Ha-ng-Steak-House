<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    use HasFactory;
    const STATUS_PENDING = 'Confirmed';
    const STATUS_COMPLETED = 'Pending';
    const STATUS_FAILED = 'Cancelled';
    protected $fillable = [
        'reservation_id', 'account_name', 'account_number', 'refund_amount', 'bank_name', 'email', 'reason', 'status'
    ];
    
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public $timestamps = false; 
}
