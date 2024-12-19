<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dishes extends Model
{
    use HasFactory, SoftDeletes; // Sử dụng cả HasFactory và SoftDeletes

    protected $dates = ['deleted_at'];

    protected $table = 'dishes';

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'status',
        'image',
        'is_active',
    ];

    // Quan hệ một-nhiều với Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ nhiều-nhiều với Combo
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_dish', 'dish_id', 'combo_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Quan hệ một-nhiều với OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }
    public function kitchen()
    {
        return $this->hasMany(Kitchen::class, 'item_id');
    }

    // Quan hệ một-nhiều với Recipe
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'dish_id'); // Một món ăn có thể có nhiều công thức
    }

    // Quan hệ nhiều-nhiều với Ingredient thông qua bảng recipes
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes')
            ->withPivot('quantity_need'); // Sử dụng quantity_need trong bảng trung gian
    }
}
