<?php

namespace App\Events;

use App\Models\Coupon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpcomingCouponEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $coupon;
    public $message;

    public function __construct(Coupon $coupon)
    {
        // Kiểm tra dữ liệu đầu vào, nếu không hợp lệ thì log lỗi và dừng lại
        if (!$coupon instanceof Coupon || empty($coupon->code)) {
            Log::error('Invalid Coupon object or missing code in UpcomingCouponEvent');
            // Dừng hàm nếu có lỗi
            return;
        }

        $this->coupon = $coupon;

        // Xử lý thông điệp cho coupon
        $this->message = $coupon->code
            ? "Mã giảm giá {$coupon->code} của bạn sẽ hết hạn trong 24 giờ."
            : "Mã giảm giá không xác định sẽ hết hạn trong 24 giờ.";

        Log::info("Phát sự kiện sắp hết hạn với mã giảm giá: {$coupon->code}, thông báo: {$this->message}");
    }

    // Định nghĩa kênh phát sóng
    public function broadcastOn()
    {
        return new Channel('coupon-channel');
    }

    // Định nghĩa sự kiện phát sóng
    public function broadcastAs()
    {
        return 'coupon.upcoming';
    }
}
