<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'item_type',
        'quantity',
        'status',
        'canceler',
        'count_cancel',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dishes::class, 'item_id');
    }
    public function combo()
    {
        return $this->belongsTo(Combo::class, 'item_id');
    }

    public function canceler()
    {
        return $this->belongsTo(User::class, 'canceler');
    }
}
