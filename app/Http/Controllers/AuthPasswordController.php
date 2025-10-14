<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthPasswordController extends Controller
{
    // Hiển thị form nhập email/username để lấy mã đặt lại
    public function forgotForm()
    {
        return view('auth.forgot');
    }

    // Nhận email/username, tạo mã xác minh (demo hiển thị ngay trên màn hình)
    public function forgotSubmit(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:120',
        ], [
            'identifier.required' => 'Vui lòng nhập email hoặc tên đăng nhập.',
        ]);

        $identifier = trim((string) $request->input('identifier'));
        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'Không tìm thấy tài khoản phù hợp.']);
        }

        $code = rand(100000, 999999);
        session(['reset_code_'.$user->id => $code, 'reset_user_'.$code => $user->id]);

        // Demo: hiển thị mã ngay trên giao diện. Triển khai thật nên gửi email.
        return redirect()->route('password.reset.form')->with('success', 'Mã xác minh của bạn là: '.$code);
    }

    // Form nhập mã và mật khẩu mới
    public function resetForm()
    {
        return view('auth.reset');
    }

    // Đặt lại mật khẩu
    public function resetSubmit(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ], [
            'code.required' => 'Vui lòng nhập mã xác minh 6 chữ số.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $code = (string) $request->input('code');
        $userId = session('reset_user_'.$code);
        if (!$userId) {
            return back()->withErrors(['code' => 'Mã xác minh không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return back()->withErrors(['code' => 'Không tìm thấy tài khoản.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Xóa mã đã dùng
        session()->forget('reset_code_'.$user->id);
        session()->forget('reset_user_'.$code);

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}

