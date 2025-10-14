<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCustomerRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('admin');
        if (!$user) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }
        if (($user->role ?? 'user') !== 'user') {
            return redirect()->route('admin.dashboard')
                ->with('warning', 'Tài khoản quản trị không thể thực hiện mua hàng.');
        }
        return $next($request);
    }
}

