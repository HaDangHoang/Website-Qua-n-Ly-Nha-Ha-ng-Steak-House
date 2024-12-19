<?php

namespace App\Events;

use App\Models\Coupon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OverdueCouponEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $coupon;
    public $message;

    public function __construct(Coupon $coupon)
    {
        // Kiểm tra và ghi log nếu dữ liệu không hợp lệ
        if (!$coupon instanceof Coupon) {
            Log::error('Invalid Coupon object passed to OverdueCouponEvent');
            return;
        }

        $this->coupon = $coupon;

        // Cập nhật trạng thái của coupon nếu chưa hết hạn
        if ($coupon->end_time < now() && $coupon->status != 'expired') {
            $coupon->update(['status' => 'expired']);
            Log::info("Trạng thái coupon {$coupon->code} đã được cập nhật thành expired");
        }

        // Kiểm tra thuộc tính code trước khi gán
        if ($coupon->code) {
            $this->message = "Mã giảm giá {$coupon->code} của bạn đã hết hạn!";
        } else {
            $this->message = "Mã giảm giá không xác định đã hết hạn!";
        }

        Log::info("Đang phát sự kiện với mã giảm giá: {$this->coupon->code}, thông báo: {$this->message}");
    }

    public function broadcastOn()
    {
        return new Channel('coupon-channel');
    }

    public function broadcastAs()
    {
        return 'coupon.overdue';
    }
}
