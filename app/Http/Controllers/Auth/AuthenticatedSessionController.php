<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị trang đăng nhập.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Xác thực người dùng
        $request->authenticate();
    
        // Tạo lại session cho người dùng
        $request->session()->regenerate();
    
        // Lấy thông tin người dùng vừa đăng nhập
        $user = Auth::user();
    
        // Kiểm tra quyền và điều hướng
        if ($user->can('access pos') && !$user->can('access admin')) {
            // Nếu chỉ có quyền 'access pos', chuyển hướng đến trang POS
            return redirect()->route('pos.index');
        }
        if ($user->can('Xem bếp') && !$user->can('access admin')) {
            // Nếu chỉ có quyền 'access pos', chuyển hướng đến trang POS
            return redirect()->route('kitchen.index');
        }
    
        // Nếu không có quyền admin và đang truy cập /admin
        if (!$user->can('access admin', 'access pos', 'Xem bếp') && request()->is('admin', 'pos', 'Xem bếp')) {
            return redirect('/');
        }
    
        // Nếu có quyền admin hoặc cả hai quyền, chuyển hướng đến trang admin
        return redirect()->intended(RouteServiceProvider::HOME);
    }
    public function adminDashboard()
{
    // Kiểm tra quyền truy cập admin
    if (!auth()->user()->can('access admin')) {
        return redirect('/');
    }

    return view('admin.dashboard');
}

    /**
     * Xóa session khi người dùng đăng xuất.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
