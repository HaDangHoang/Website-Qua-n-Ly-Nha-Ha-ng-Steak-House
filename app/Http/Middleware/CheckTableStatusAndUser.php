<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\Table;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckTableStatusAndUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    const MAX_VISITORS = 4;
    const TOKEN_EXPIRATION = 300;

    public function handle(Request $request, Closure $next): Response
    {
        $tableId = $request->input('data');
        $table = Table::find($tableId);

        if (!$table || $table->status !== 'Occupied') {
            return redirect()->route('client.index')->with('err', 'Vui lòng đến quầy lễ tân đặt bàn trước!');
        }

        if (!Auth::check()) {
            Session::put('url.intended', url()->current());
            return redirect()->guest(route('login.form'));
        }

        $userId = Auth::id();
        $order = $table->orders->where('status', 'pending')->first();

        if ($order) {
            $customerId = $order->reservation ? $order->reservation->customer->id : $order->customer->id;
            if ($customerId !== $userId) {
                return redirect()->route('client.index')->with('err', 'Người dùng không có quyền truy cập bàn này!');
            }
        }
        $tabToken = $request->cookie('tab_token');
        if (!$tabToken) {
            $tabToken = bin2hex(random_bytes(16));
            cookie()->queue('tab_token', $tabToken, 60);
        }
        $userTabKey = "user_{$userId}_tabs";
        $lockKey = "lock_{$userTabKey}";
        $lock = Cache::lock($lockKey, 5);
        if ($lock->get()) {
            try {
                $userTabs = Cache::get($userTabKey, []);
                $currentTimestamp = now()->timestamp;
                $userTabs = array_filter($userTabs, function ($timestamp) use ($currentTimestamp) {
                    return $timestamp + self::TOKEN_EXPIRATION > $currentTimestamp;
                });

                if (!isset($userTabs[$tabToken])) {
                    if (count($userTabs) >= self::MAX_VISITORS) {
                        return redirect()->route('client.index')->with('err', 'Số lượng người truy cập đã đạt tối đa!');
                    }
                    $userTabs[$tabToken] = $currentTimestamp;
                    Cache::put($userTabKey, $userTabs, self::TOKEN_EXPIRATION);
                }
            } finally {
                $lock->release();
            }
        } else {
            return redirect()->route('client.index')->with('err', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
        return $next($request);
    }
}
