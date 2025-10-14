<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;

class EnsureAdminSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }
        // Once per session: reassign legacy orders (user_id NULL) that match username
        if (!session('orders_reassigned')) {
            try {
                $user = session('admin');
                if ($user) {
                    Order::whereNull('user_id')
                        ->where('customer_name', $user->username)
                        ->update(['user_id' => $user->id]);
                }
            } catch (\Throwable $e) {
                // ignore silently
            }
            session(['orders_reassigned' => true]);
        }
        return $next($request);
    }
}
