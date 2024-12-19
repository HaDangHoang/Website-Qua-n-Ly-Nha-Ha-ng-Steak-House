<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;
use App\Events\UpcomingCouponEvent;
use App\Events\OverdueCouponEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckCouponsExpiry extends Command
{
    protected $signature = 'coupons:check-expiry';
    protected $description = 'Kiểm tra và cập nhật trạng thái mã giảm giá';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now('UTC'); // Đảm bảo múi giờ thống nhất

        // Duyệt qua mã giảm giá theo từng nhóm nhỏ để tiết kiệm tài nguyên
        Coupon::where('status', '!=', 'expired')
            ->chunk(100, function ($coupons) use ($now) {
                foreach ($coupons as $coupon) {
                    $endTime = Carbon::parse($coupon->end_time, 'UTC');

                    // Kiểm tra nếu mã giảm giá còn 24 giờ nữa sẽ hết hạn
                    if ($endTime->isFuture() && $endTime->diffInHours($now) <= 24) {
                        event(new UpcomingCouponEvent($coupon));
                    }

                    // Kiểm tra nếu mã giảm giá đã hết hạn
                    if ($endTime->isPast()) {
                        if ($coupon->update(['status' => 'expired'])) {
                            event(new OverdueCouponEvent($coupon));
                        } else {
                            Log::error("Failed to update status for coupon ID: {$coupon->id}");
                        }
                    }
                }
            });

        $this->info('Coupons checked and updated successfully!');
    }
}
