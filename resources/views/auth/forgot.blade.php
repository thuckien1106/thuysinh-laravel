@extends('layouts.header')
@section('title', 'Quên mật khẩu')

@section('content')

<style>
    /* ================= FORGOT PASSWORD THEME ================= */
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

    .auth-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    /* Auth Card */
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        width: 100%;
        max-width: 480px;
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

    /* Decoration */
    .auth-card::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 140px;
        height: 140px;
        background: radial-gradient(circle, rgba(13,148,136,0.15) 0%, rgba(255,255,255,0) 70%);
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

    .icon-box {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 16px;
        box-shadow: 0 8px 20px rgba(13, 148, 136, 0.25);
    }

    /* Input */
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
    .btn-submit {
        background: linear-gradient(to right, #0d9488, #14b8a6);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        color: white;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
        color: white;
    }

    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
    }
    .back-link:hover {
        color: var(--text-dark);
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="icon-box">
                <i class="bi bi-key-fill"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Quên mật khẩu?</h3>
            <p class="text-muted small mb-0">Nhập email hoặc tên đăng nhập của bạn để đặt lại mật khẩu</p>
        </div>

        <form method="POST" action="{{ route('password.forgot.submit') }}">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Email hoặc Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="text" class="form-control form-control-custom" name="identifier" 
                           placeholder="Nhập thông tin của bạn..." required autofocus>
                </div>
            </div>

            <button class="btn btn-submit w-100 mb-4">
                <i class="bi bi-send me-2"></i> Gửi mã xác nhận
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i> Quay lại đăng nhập
                </a>
            </div>
        </form>
    </div>
</div>

@include('layouts.footer')

@endsection