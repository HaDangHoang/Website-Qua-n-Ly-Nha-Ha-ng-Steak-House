<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    use HasFactory;
    protected $table = 'inventory_stocks'; // Đặt tên bảng đúng
    public $timestamps = false;
    protected $fillable = ['ingredient_id', 'quantity_stock', 'quantity_reserved', 'last_update'];
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
