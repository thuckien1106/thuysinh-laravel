<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('admin');
        if (!$user) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }
        if (($user->role ?? 'user') !== 'admin') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập khu vực quản trị.');
        }
        return $next($request);
    }
}

