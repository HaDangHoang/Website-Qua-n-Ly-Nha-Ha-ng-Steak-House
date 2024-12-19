<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CustomerAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('client.loginOTP');
    }

    public function login(Request $request)
    {
        // Xác thực thông tin đầu vào
        $request->validate([
            'phone' => 'required|string',
            'username' => 'required|string',
        ]);

        // Tìm người dùng theo số điện thoại
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            // Nếu không tìm thấy, tạo tài khoản mới
            $user = User::create([
                'phone' => $request->phone,
            ]);
        }

        // Đăng nhập người dùng
        Auth::login($user);

        // Chuyển đến trang xác thực OTP
        return response()->json(['success' => true, 'message' => 'Tài khoản đã được tạo hoặc tồn tại. Vui lòng xác thực OTP.']);
    }

    public function verifyCode(Request $request)
    {
        // Chỉ cần xác thực phone và verificationCode
        $request->validate([
            'verificationCode' => 'required|string',
            'phone' => 'required|string|max:15',
        ]);

        // Kiểm tra xem số điện thoại đã tồn tại trong database chưa
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            // Nếu không tồn tại, tạo tài khoản mới với tên mặc định hoặc tên không cần thiết
            $user = User::create([
                'name' => 'User_' . uniqid(), // Tạo tên ngẫu nhiên
                'phone' => $request->phone,
            ]);
        }

        // Đăng nhập người dùng
        Auth::login($user);
        $intendedUrl = Session::pull('url.intended', '/');
        // Chuyển hướng đến trang chính
        return response()->json(['success' => true, 'message' => 'Xác thực OTP thành công và đăng nhập.', 'redirect_url' => $intendedUrl]);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('client.index');
    }

}
