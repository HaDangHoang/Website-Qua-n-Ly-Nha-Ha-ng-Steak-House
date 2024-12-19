<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show()
    {
        // Giả lập dữ liệu thành viên, bạn có thể lấy từ database
        $member = auth()->user(); // Lấy thông tin người dùng đã đăng nhập
        
        // Nếu không có người dùng đã đăng nhập, chuyển hướng về trang đăng nhập hoặc xử lý lỗi
        if (!$member) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem trang này.');
        }
    
        // Lấy tất cả thông tin đơn đặt bàn của khách hàng
        $bookingData = Reservation::where('customer_id', $member->id)
            ->orderBy('reservation_date', 'desc') // Sắp xếp theo ngày đặt
            ->get(); // Lấy tất cả đơn đặt bàn
    
        return view('client.member', compact('member', 'bookingData')); // Truyền biến $member và $bookingData vào view
    }
    public function update(Request $request)
    {
        // Xử lý cập nhật thông tin tài khoản ở đây
        // Ví dụ: Cập nhật tên, số điện thoại, email...

        return back()->with('success', 'Thông tin tài khoản đã được cập nhật');
    }

    public function changePassword(Request $request)
    {
        // Xử lý thay đổi mật khẩu ở đây

        return back()->with('success', 'Mật khẩu đã được thay đổi');
    }
}
