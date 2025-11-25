<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function loginForm()
    {
        if (session('admin')) {
            $role = session('admin')->role ?? 'user';
            return $role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('home');
        }
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $identifier = trim((string) $request->input('username'));
        $password = (string) $request->input('password');

        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if ($user) {
            $isValid = false;
            if (Hash::check($password, $user->password)) {
                $isValid = true;
            } elseif ($user->password === $password) {
                $isValid = true;
                // Nâng cấp mật khẩu plain-text thành bcrypt sau khi đăng nhập lần đầu
                try { $user->password = Hash::make($password); $user->save(); } catch (\Throwable $e) {}
            }
            if ($isValid) {
                DB::table('customers')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'full_name' => $user->name ?? $user->username,
                        'email' => $user->email,
                    ]
                );
                session(['admin' => $user]);
                return $user->role === 'admin'
                    ? redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!')
                    : redirect()->route('home')->with('success', 'Chào mừng bạn quay lại, ' . $user->username . '!');
            }
        }

        return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.']);
    }

    // Hiển thị form đăng ký
    public function register()
    {
        if (session('admin')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function registerProcess(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|min:4|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->username;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công.');
    }
}
