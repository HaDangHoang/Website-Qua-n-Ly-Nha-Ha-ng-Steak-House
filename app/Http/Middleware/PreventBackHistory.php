<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // Chuyển hướng nếu đã đăng nhập
            return redirect('/'); // Hoặc bất kỳ route nào mà bạn muốn
        }

        return $next($request);
    }
}
