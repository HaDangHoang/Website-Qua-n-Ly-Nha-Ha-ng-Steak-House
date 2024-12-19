<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_type', 'total_amount', 'description', 'supplier_id', 'staff_id', 'created_at','updated_at', 'status'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
