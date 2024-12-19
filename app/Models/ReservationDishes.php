<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationDishes extends Model
{
    protected $table = 'reservation_dishes';

    protected $fillable = [
        'reservation_id',
        'dish_id',
        'quantity',
        'price',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dishes::class);
    }
}
