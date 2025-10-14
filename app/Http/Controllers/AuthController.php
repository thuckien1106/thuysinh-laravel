<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form
    public function loginForm()
    {
        if (session('admin')) {
            $role = session('admin')->role ?? 'user';
            return $role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('home');
        }
        return view('auth.login');
    }

    // Handle login
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
                // Nâng cấp mật khẩu plain-text thành bcrypt ngay sau lần đăng nhập đầu
                try { $user->password = Hash::make($password); $user->save(); } catch (\Throwable $e) {}
            }
            if ($isValid) {
                session(['admin' => $user]);
                return $user->role === 'admin'
                    ? redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!')
                    : redirect()->route('home')->with('success', 'Chào mừng bạn quay lại, ' . $user->username . '!');
            }
        }

        return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.']);
    }

    // Show register form
    public function register()
    {
        if (session('admin')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // Handle register
    public function registerProcess(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|min:4|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công.');
    }
}
