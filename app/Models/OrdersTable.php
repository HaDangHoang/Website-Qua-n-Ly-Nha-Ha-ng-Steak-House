<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersTable extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['order_id', 'table_id', 'start_time', 'end_time', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
