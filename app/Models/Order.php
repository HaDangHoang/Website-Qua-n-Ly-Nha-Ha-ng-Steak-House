<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'orders';

    // Các trường có thể thêm vào hoặc chỉnh sửa từ form
    protected $fillable = [
        'reservation_id',
        'staff_id',
        'customer_id',
        'guest_count',
        'total_amount',
        'order_type',
        'status',
        'discount_amount',
        'final_amount',
    ];

    // Trường lưu trữ ngày khi sử dụng soft deletes
    protected $dates = ['deleted_at'];

    // === Định nghĩa các quan hệ ===

    // Quan hệ với bảng Reservation (Đặt chỗ)
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    // Quan hệ với bảng User (Nhân viên phụ trách đơn hàng)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // Quan hệ với bảng Table (Bàn mà đơn hàng thuộc về)
    public function tables()
    {
        return $this->belongsToMany(Table::class, 'orders_tables')
            ->withPivot('start_time', 'end_time', 'status');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Quan hệ với bảng User (Khách hàng)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Quan hệ với bảng Coupon (Mã giảm giá)
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Quan hệ với bảng OrderItem (Các món ăn thuộc đơn hàng)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // === Các phương thức tính toán và xử lý đơn hàng ===

    // Tính tổng số tiền đơn hàng từ các món ăn
    public function calculateTotalAmount()
    {
        return $this->orderItems()->sum('total_price');
    }

    // Tính toán số tiền cuối cùng sau khi áp dụng giảm giá
    public function calculateFinalAmount()
    {
        $total = $this->calculateTotalAmount();
        $discount = $this->discount_amount ?? 0; // Kiểm tra xem có giảm giá không
        return max(0, $total - $discount); // Số tiền cuối cùng không được âm
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save(); // Lưu thay đổi trạng thái vào cơ sở dữ liệu
    }

    // Kiểm tra xem đơn hàng có món ăn hay không
    public function hasItems()
    {
        return $this->orderItems()->count() > 0; // Kiểm tra xem có món ăn nào trong đơn hàng không
    }

    // Kiểm tra đơn hàng có mã giảm giá không
    public function hasCoupon()
    {
        return !is_null($this->coupon_id); // Kiểm tra có áp dụng mã giảm giá hay không
    }
    public function orderTables()
{
    return $this->belongsToMany(OrdersTable::class, 'order_table_order', 'order_id', 'order_table_id');
}

}
