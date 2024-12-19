<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class CheckReservationStatus extends Command
{
    protected $signature = 'reservations:check-status';
    protected $description = 'Check upcoming and overdue reservations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lấy thời gian hiện tại
        $now = Carbon::now();

        // Lấy danh sách các đơn hàng sắp đến hạn (trong 30 phút)
        $upcomingReservations = Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '>=', $now->toTimeString())
            ->where('reservation_time', '<=', $now->addMinutes(30)->toTimeString())
            ->where('status', 'Pending')
            ->get();
        // Thông báo cho các đơn hàng sắp đến hạn
        foreach ($upcomingReservations as $reservation) {
            event(new \App\Events\UpcomingReservationEvent($reservation));
        }

        // Kiểm tra và cập nhật trạng thái cho các đơn đặt bàn quá hạn
        Reservation::whereDate('reservation_date', $now->toDateString())
            ->whereTime('reservation_time', '<', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Pending')
            ->update(['status' => 'Cancelled']);

        // Lấy danh sách các đơn hàng đã quá hạn
        $overdueReservations = Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->toTimeString())
            ->where('status', 'Pending')
            ->get();

        // Cập nhật trạng thái và thông báo cho các đơn đã quá hạn
        foreach ($overdueReservations as $reservation) {
            $reservation->update(['status' => 'Cancelled']);
            event(new \App\Events\OverdueReservationEvent($reservation));
        }

        $this->info('Reservation statuses checked successfully.');
    }
}