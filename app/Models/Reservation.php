<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $table = 'reservations';

    protected $fillable = [
        'id',
        'customer_id',
        'user_name',
        'user_phone',
        'coupon_id',
        'reservation_time',
        'reservation_date',
        'guest_count',
        'deposit_amount',
        'status',
        'cancelled_reason',
        'note',
        'review',
    ];

    protected $dates = ['deleted_at'];
    
    public function orders(){
        return $this->hasMany(Order::class,'reservation_id');
    }


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
    public function tables()
    {
        return $this->belongsToMany(Table::class, 'reservation_tables');

    }
    public function refund()
    {
        return $this->hasOne(Refund::class, 'reservation_id', 'id');
    }
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'reservation_id', 'id');
    }



}

