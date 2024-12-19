<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationHistory extends Model
{
    protected $table = 'reservation_history';

    protected $fillable = [
        'reservation_id',
        'change_time',
        'status',
        'note',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
