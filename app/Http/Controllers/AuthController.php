<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * 🟦 Hiển thị form đăng nhập
     */
    public function loginForm()
    {
        // Nếu đã đăng nhập thì chuyển hướng về trang phù hợp
        if (session('admin')) {
            $role = session('admin')->role;
            return $role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * 🟩 Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Tìm user theo username hoặc email
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        // Kiểm tra mật khẩu
        if ($user && Hash::check($request->password, $user->password)) {
            // Lưu thông tin user vào session
            session(['admin' => $user]);

            // Chuyển hướng theo vai trò
            if ($user->role === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Đăng nhập thành công!');
            }

            return redirect()
                ->route('home')
                ->with('success', 'Chào mừng bạn quay lại, ' . $user->username . '!');
        }

        // Sai thông tin
        return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.']);
    }

    /**
     * 🟧 Hiển thị form đăng ký
     */
    public function register()
    {
        // Nếu đã đăng nhập thì không được đăng ký nữa
        if (session('admin')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * 🟨 Xử lý đăng ký người dùng mới
     */
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

        // Tạo user mới
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user'; // Mặc định là user thường
        $user->save();

        return redirect()
            ->route('login.form')
            ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    /**
     * 🟥 Đăng xuất
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        $request->session()->flush();

        return redirect()
            ->route('login.form')
            ->with('success', 'Đã đăng xuất thành công.');
    }
}
