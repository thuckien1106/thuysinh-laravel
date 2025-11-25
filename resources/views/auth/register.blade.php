@extends('layouts.header')
@section('title', 'Đăng ký tài khoản')

@section('content')

<style>
    /* ================= REGISTER PAGE THEME (Đồng bộ Login) ================= */
    :root {
        --primary-color: #0d9488; /* Teal 600 */
        --primary-hover: #0f766e; /* Teal 700 */
        --bg-color: #f0fdfa; /* Teal 50 */
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background-color: var(--bg-color);
        background-image: radial-gradient(#ccfbf1 1px, transparent 1px);
        background-size: 24px 24px;
    }

    .register-container {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    /* Auth Card */
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        width: 100%;
        max-width: 500px; /* Rộng hơn login một chút */
        border-radius: 24px;
        box-shadow: 0 20px 40px -10px rgba(13, 148, 136, 0.15);
        padding: 40px;
        border: 1px solid rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Trang trí background card */
    .auth-card::before {
        content: '';
        position: absolute;
        top: -80px;
        left: -80px;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(13,148,136,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .auth-card::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(45, 212, 191, 0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }

    /* Header */
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }

    .brand-logo {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 26px;
        margin-bottom: 12px;
        box-shadow: 0 6px 15px rgba(13, 148, 136, 0.25);
    }

    /* Inputs */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
    }
    
    .input-group-text {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-right: none;
        color: #94a3b8;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        padding-left: 16px;
    }

    .form-control-custom {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.2s;
        border-left: none;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    .form-control-custom:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: none;
        border-left: 1px solid var(--primary-color);
    }
    .form-control-custom:focus + .input-group-text, 
    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
        background-color: white;
        color: var(--primary-color);
    }

    /* Button */
    .btn-register {
        background: linear-gradient(to right, #0d9488, #14b8a6);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        color: white;
        margin-top: 10px;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
        color: white;
    }

    /* Links */
    .login-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 700;
        position: relative;
    }
    .login-link::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        bottom: -2px;
        left: 0;
        background-color: currentColor;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }
    .login-link:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
</style>

<div class="register-container">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="brand-logo">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Tạo tài khoản mới</h3>
            <p class="text-muted small">Tham gia cùng AquaShop ngay hôm nay</p>
        </div>

        <form method="POST" action="{{ route('register.process') }}">
            @csrf

            <div class="row g-3">
                <!-- Username -->
                <div class="col-12">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control form-control-custom" placeholder="Chọn tên đăng nhập..." required autofocus>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label class="form-label">Xác nhận</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                        <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button class="btn btn-register w-100 mt-4 mb-4">
                Đăng ký tài khoản
            </button>

            <!-- Footer Link -->
            <div class="text-center">
                <span class="text-muted small">Đã có tài khoản?</span>
                <a href="{{ route('login.form') }}" class="login-link ms-1">
                    Đăng nhập ngay
                </a>
            </div>
        </form>
    </div>
</div>

@include('layouts.footer')

@endsection