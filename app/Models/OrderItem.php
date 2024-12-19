<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;

    // Xác định tên bảng (nếu khác với quy ước mặc định)
    protected $table = 'order_items';

    // Các trường có thể được gán giá trị một cách hàng loạt
    protected $fillable = [
        'order_id',
        'item_id', // Dùng item_id để lưu id của món ăn hoặc combo
        'item_type', // Lưu loại của mục là 'dish' hoặc 'combo'
        'quantity',
        'price',
        'total_price',
        'status', // Trạng thái của món (preparing, served, etc.)
        'cancel_reason',
        'informed',
        'processing',
        'completed',
        'deleted_at',
    ];
    public $timestamps = true;
    /**
     * Mối quan hệ với model Order (mỗi mục thuộc về một đơn hàng)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ với model Dishes (món ăn)
     * Sử dụng khi item_type là 'dish'
     */
    public function dish()
    {
        return $this->belongsTo(Dishes::class, 'item_id');
    }

    /**
     * Mối quan hệ với model Combo (combo món ăn)
     * Sử dụng khi item_type là 'combo'
     */
    public function combo()
    {
        return $this->belongsTo(Combo::class, 'item_id');
    }

    /**
     * Phương thức giúp xác định kiểu item (món ăn hoặc combo)
     * Tùy vào giá trị của item_type, trả về model phù hợp
     */
    public function getItem()
    {
        if ($this->item_type == 'dish') {
            return $this->dish;
        } elseif ($this->item_type == 'combo') {
            return $this->combo;
        }
        return null;
    }
}
