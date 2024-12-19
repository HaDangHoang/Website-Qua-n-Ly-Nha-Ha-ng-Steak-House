<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['ingredient_id', 'inventory_transaction_id', 'quantity', 'unit_price'];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class);
    }
}
