<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'description',
        'max_uses',
        'start_time',
        'end_time',
        'discount_type',
        'discount_amount',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
