<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Reservation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem thống kê', ['only' => ['index']]);
        
    }

    public function index(Request $request)
    {
        $title = 'Báo Cáo Thống Kê';

        // Tính tổng số đơn đặt bàn theo trạng thái 'Confirmed', 'Pending', 'Completed'
        $totalReservationsConfirmed = Reservation::where('status', 'Confirmed')->count();
        $totalReservationsPending = Reservation::where('status', 'Pending')->count();
        $totalReservationsCompleted = Reservation::where('status', 'Completed')->count();

        // Cộng tất cả các đơn đặt bàn
        $totalReservations = $totalReservationsConfirmed + $totalReservationsPending + $totalReservationsCompleted;

        // Tính tổng số lượng khách theo các trạng thái 'Confirmed', 'Pending', 'Completed'
        $totalGuestsConfirmed = Reservation::where('status', 'Confirmed')->sum(DB::raw('COALESCE(guest_count, 0)'));
        $totalGuestsPending = Reservation::where('status', 'Pending')->sum(DB::raw('COALESCE(guest_count, 0)'));
        $totalGuestsCompleted = Reservation::where('status', 'Completed')->sum(DB::raw('COALESCE(guest_count, 0)'));

        // Cộng tất cả số lượng khách
        $totalGuests = $totalGuestsConfirmed + $totalGuestsPending + $totalGuestsCompleted;

        // Tính tổng số món ăn
        $dishCount = Dishes::count();

        // Tính tổng số đơn hàng
        $orderCount = Order::count();

        // Tính tổng tiền cọc từ các đơn đặt bàn đã xác nhận
        $totalDeposit = Reservation::where('status', 'Confirmed')
            ->sum(DB::raw('COALESCE(deposit_amount, 0)'));

        // Tính tổng tiền cọc của tất cả các đơn đặt bàn (bao gồm cả những đơn không có cọc)
        $totalDepositAll = Reservation::sum(DB::raw('COALESCE(deposit_amount, 0)'));

        // Tính tỷ lệ % cọc
        $depositPercentage = 0;
        if ($totalDepositAll > 0) {
            $depositPercentage = ($totalDeposit / $totalDepositAll) * 100;
        }



        // Tính tổng tiền hoàn lại chỉ khi trạng thái là 'Refund'
        $totalRefund = Refund::where('status', 'Refund')
            ->sum(DB::raw('COALESCE(refund_amount, 0)'));

        // Tính tổng tiền hoàn lại của cả hai trạng thái 'Refund' và 'Request_Refund' để tính % hoàn lại
        $totalRefundAll = Refund::whereIn('status', ['Refund', 'Request_Refund'])
            ->sum(DB::raw('COALESCE(refund_amount, 0)'));

        // Tính tỷ lệ % hoàn lại
        $refundPercentage = 0;
        if ($totalRefundAll > 0) {
            $refundPercentage = ($totalRefund / $totalRefundAll) * 100;
        }

        // **Tính lợi nhuận**
        $totalProfit = DB::table('dishes')
            ->leftJoin('recipes', 'dishes.id', '=', 'recipes.dish_id')
            ->leftJoin('ingredients', 'recipes.ingredient_id', '=', 'ingredients.id')
            ->selectRaw('SUM(dishes.price - COALESCE((recipes.quantity_need * ingredients.price), 0)) as total_profit')
            ->value('total_profit');

        // **Tính lợi nhuận tháng trước**
        $previousProfit = 70000; // Giả định giá trị lấy từ database hoặc cấu hình

        // **Tính tỷ lệ % thay đổi lợi nhuận**
        $profitChangePercentage = 0;
        if ($previousProfit != 0) {
            $profitChangePercentage = (($totalProfit - $previousProfit) / abs($previousProfit)) * 100;
        }

        // Làm tròn phần trăm, ví dụ làm tròn đến 1 chữ số thập phân
        $profitChangePercentage = round($profitChangePercentage, 1);





        $totalRevenue = DB::table('orders')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->value('total_revenue');

        $previousRevenue = 70000; // Giả định doanh thu tháng trước, có thể lấy từ cơ sở dữ liệu

        $revenueChangePercentage = 0;
        if ($previousRevenue > 0) {
            $revenueChangePercentage = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        }

        // Làm tròn tỷ lệ phần trăm
        $revenueChangePercentage = round($revenueChangePercentage, 1);



        // Lấy số lượng đặt bàn và số lượng khách theo từng tháng
        $chartData = DB::table('reservations')
            ->select(
                DB::raw('MONTH(reservation_date) as month'),
                DB::raw('COUNT(id) as totalReservations'),
                DB::raw('SUM(guest_count) as totalGuests')
            )
            ->whereNotNull('reservation_date') // Chỉ lấy các ngày không null
            ->groupBy(DB::raw('MONTH(reservation_date)'))
            ->orderBy('month')
            ->get();

        // Định dạng dữ liệu để truyền vào view
        $reservations = array_fill(1, 12, 0); // Khởi tạo mảng với 12 tháng, giá trị mặc định là 0
        $guests = array_fill(1, 12, 0);

        foreach ($chartData as $data) {
            $reservations[$data->month] = $data->totalReservations;
            $guests[$data->month] = $data->totalGuests;
        }



        // Dữ liệu cho biểu đồ khách cọc và không cọc
        $depositData = [
            'coc' => Reservation::whereMonth('created_at', Carbon::now()->month)->where('deposit_amount', '>', 0)->sum('deposit_amount'),
            'khongCoc' => Reservation::whereMonth('created_at', Carbon::now()->month)->where('deposit_amount', '=', 0)->sum('deposit_amount'),
        ];
        // Tính toán số lượng khách cọc và không cọc (sử dụng deposit_amount thay vì deposit_status)
        $coc = Reservation::where('deposit_amount', '>', 0)->count(); // Khách đã cọc
        $khongCoc = Reservation::where('deposit_amount', '=', 0)->count(); // Khách chưa cọc




        // Lấy tất cả danh mục
        $categories = Category::all();

        // Lấy số lượng món ăn cho mỗi danh mục
        $dishCounts = $categories->map(function ($category) {
            $category->dish_count = $category->dishes->count(); // Số món ăn trong mỗi danh mục
            return $category;
        });



        $data = DB::table('reservations')
            ->selectRaw('MONTH(reservation_date) as month, YEAR(reservation_date) as year')  // Lấy tháng và năm
            ->selectRaw('SUM(guest_count) as total_guests')  // Tổng số lượng khách
            ->selectRaw('SUM(deposit_amount) as total_deposits')  // Tổng số tiền cọc
            ->where('guest_count', '>=', 6)  // Chỉ lấy các đơn có từ 6 khách trở lên
            ->whereNotNull('reservation_date')  // Đảm bảo ngày đặt không rỗng
            ->groupBy(DB::raw('YEAR(reservation_date), MONTH(reservation_date)'))  // Nhóm theo năm và tháng
            ->orderByRaw('YEAR(reservation_date), MONTH(reservation_date)')  // Sắp xếp theo năm và tháng
            ->get();



        // Tính tổng tiền hoàn lại chỉ khi trạng thái là 'Refund'
        $totalRefund = Refund::where('status', 'Refund')
            ->sum(DB::raw('COALESCE(refund_amount, 0)'));

        // Tổng tiền hoàn lại cho các trạng thái 'Refund' và 'Request_Refund'
        $totalRefundAll = Refund::whereIn('status', ['Refund', 'Request_Refund'])
            ->sum(DB::raw('COALESCE(refund_amount, 0)'));

        // Tổng tiền đã hoàn lại
        $totalRefund = Refund::where('status', 'Refund')
            ->sum(DB::raw('COALESCE(refund_amount, 0)'));

        // Tổng số tiền chưa hoàn lại (số tiền còn lại sau khi trừ số tiền đã hoàn lại)
        $totalPendingRefund = $totalRefundAll - $totalRefund;


        // Tính tỷ lệ % hoàn lại
        $refundPercentage = 0;
        if ($totalRefundAll > 0) {
            $refundPercentage = ($totalRefund / $totalRefundAll) * 100;
        }


        // Lấy dữ liệu doanh thu từ bảng orders
        $revenues = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_revenue') // Sửa total_price thành total_amount
            ->groupByRaw('MONTH(created_at)')
            ->get();





        $monthlyRevenues = DB::table('dishes')
            ->selectRaw('MONTH(created_at) AS month, SUM(price) AS total_revenue')
            ->whereNotNull('created_at')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Chuẩn bị dữ liệu cho biểu đồ
        $monthLabels = $monthlyRevenues->map(function ($revenue) {
            return "Tháng " . $revenue->month;
        })->toArray();

        $monthData = $monthlyRevenues->map(function ($revenue) {
            return $revenue->total_revenue;
        })->toArray();



        // Lấy số lượng combo theo trạng thái Active và Inactive
        $activeComboCount = Combo::where('is_active', 1)->count();
        $inactiveComboCount = Combo::where('is_active', 0)->count();

        // Lấy số lượng dishes theo trạng thái Active và Inactive
        $activeDishesCount = Dishes::where('is_active', 1)->count();
        $inactiveDishesCount = Dishes::where('is_active', 0)->count();







        // Pass data to view
        return view('admin.dashboard.reports.index', compact(
            'title',
            'totalReservations',
            'totalGuests',
            'dishCount',
            'orderCount',
            'totalDeposit',
            'depositPercentage',
            'totalDepositAll',
            'totalRefund',
            'refundPercentage',
            'totalProfit',
            'profitChangePercentage',
            'totalRevenue',
            'revenueChangePercentage',
            'totalRefundAll',
            'reservations',
            'guests',
            'depositData',
            'coc',
            'khongCoc',
            'categories',
            'dishCounts',
            'data',
            'chartData',
            'totalPendingRefund',
            'totalRefundAll',
            'totalRefund',
            'revenues',
            'monthLabels',
            'monthData',
            'activeComboCount',
            'inactiveComboCount',
            'activeDishesCount',
            'inactiveDishesCount',




        ));
    }
}
