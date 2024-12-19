<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
