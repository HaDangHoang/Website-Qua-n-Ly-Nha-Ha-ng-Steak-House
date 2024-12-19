<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combo extends Model
{
    use SoftDeletes;

    protected $table = 'combos';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_active',
        'quantity_dishes',
    ];

    protected $dates = ['deleted_at'];

    public function dishes()
    {
        return $this->belongsToMany(Dishes::class, 'combo_dish', 'combo_id', 'dish_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }
    public function kitchen()
    {
        return $this->hasMany(Kitchen::class, 'item_id');
    }
}

