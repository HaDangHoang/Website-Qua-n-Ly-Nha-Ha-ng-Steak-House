<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'dish_id',
        'ingredient_id',
        'quantity_need',
    ];

    public function dish()
    {
        return $this->belongsTo(Dishes::class, 'dish_id'); // Chỉ định khóa ngoại là 'dish_id'
    }

    /**
     * Thiết lập quan hệ với Ingredient.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id'); // Chỉ định khóa ngoại là 'ingredient_id'
    }
}
