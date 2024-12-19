<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và là admin
        if (Auth::check() && Auth::user()->role->name === 'admin') {
            return $next($request); // Nếu là admin, tiếp tục truy cập
        }

        // Nếu không phải admin hoặc chưa đăng nhập, chuyển đến trang đăng nhập
        return redirect()->route('logon')->with('error', 'You must be an admin to access this page.');
    }
}
