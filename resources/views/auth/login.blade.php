@extends('layouts.header')
@section('title', 'Đăng nhập tài khoản')

@section('content')

<style>
    /* ================= LOGIN PAGE THEME ================= */
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

    .login-container {
        min-height: 85vh; /* Cân đối chiều cao màn hình */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    /* Card chính */
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        width: 100%;
        max-width: 460px;
        border-radius: 24px;
        box-shadow: 0 20px 40px -10px rgba(13, 148, 136, 0.15);
        padding: 48px 40px;
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

    /* Trang trí góc card */
    .auth-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(13,148,136,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }

    /* Header */
    .auth-header {
        text-align: center;
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }

    .brand-logo {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        margin-bottom: 16px;
        box-shadow: 0 8px 20px rgba(13, 148, 136, 0.25);
        transform: rotate(-5deg);
        transition: transform 0.3s;
    }
    .auth-card:hover .brand-logo {
        transform: rotate(0deg) scale(1.05);
    }

    /* Input Fields */
    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    
    .input-group-text {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-right: none;
        color: #94a3b8;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .form-control-custom {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.2s;
        border-left: none; /* Liền mạch với icon */
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    .form-control-custom:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: none; /* Tắt shadow mặc định của BS */
        border-left: 1px solid var(--primary-color); /* Hiện lại border trái khi focus */
    }
    /* Khi input focus thì đổi màu icon */
    .form-control-custom:focus + .input-group-text, 
    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
        background-color: white;
        color: var(--primary-color);
    }

    /* Button */
    .btn-login {
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
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
        color: white;
    }

    /* Links & Checkbox */
    .form-check-input {
        cursor: pointer;
        width: 1.1em;
        height: 1.1em;
    }
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .forgot-link {
        color: var(--text-muted);
        font-size: 0.9rem;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    .forgot-link:hover {
        color: var(--primary-color);
    }

    .register-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 700;
        position: relative;
    }
    .register-link::after {
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
    .register-link:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
</style>

<div class="login-container">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="brand-logo">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Chào mừng trở lại!</h3>
            <p class="text-muted small">Đăng nhập để tiếp tục quản lý AquaShop</p>
        </div>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <!-- Username Input -->
            <div class="mb-4">
                <label class="form-label">Tài khoản</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" 
                           class="form-control form-control-custom" 
                           placeholder="Email hoặc tên đăng nhập" required autofocus>
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" 
                           class="form-control form-control-custom" 
                           placeholder="Nhập mật khẩu..." required>
                </div>
            </div>

            <!-- Remember & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label small text-secondary pt-1" for="rememberMe">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <a href="{{ route('password.forgot') }}" class="forgot-link">
                    Quên mật khẩu?
                </a>
            </div>

            <!-- Submit Button -->
            <button class="btn btn-login w-100 mb-4">
                <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập
            </button>

            <!-- Register Footer -->
            <div class="text-center pt-2">
                <span class="text-muted small">Bạn chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="register-link ms-1">
                    Đăng ký ngay
                </a>
            </div>
        </form>
    </div>
</div>

@include('layouts.footer')

@endsection