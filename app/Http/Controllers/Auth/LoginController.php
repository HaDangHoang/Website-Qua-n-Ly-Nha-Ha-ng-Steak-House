<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('client.login');  // Trả về form đăng nhập
    }

    public function login(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Thử đăng nhập
        if (Auth::attempt($credentials)) {
            // Nếu thành công, chuyển hướng về trang chủ
            return redirect()->intended('/');
        }

        // Nếu thất bại, quay lại trang đăng nhập với thông báo lỗi tiếng Việt
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }
}
