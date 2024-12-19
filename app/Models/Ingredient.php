<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';

    // Các cột có thể điền vào để tránh lỗi MassAssignmentException
    protected $fillable = [
        'name',
        'price',
        'unit',
        'category',
    ];

    /**
     * Thiết lập quan hệ một-một với InventoryStock
     */
    public function inventoryStock()
    {
        return $this->hasOne(InventoryStock::class);
    }

    /**
     * Thiết lập quan hệ một-nhiều với InventoryItem
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Thiết lập quan hệ với Recipe (có thể nhiều hơn một)
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class); // Sử dụng hasMany nếu nguyên liệu có thể có nhiều công thức
    }

    /**
     * Thiết lập quan hệ với Dish thông qua bảng recipes
     */
    public function dishes()
    {
        return $this->belongsToMany(Dishes::class, 'recipes')->withPivot('quantity_need'); // Đảm bảo trường đúng là quantity_need
    }
}
