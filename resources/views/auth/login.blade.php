@extends('layouts.header')
@section('title', 'Đăng nhập')
@section('content')

<style>
/* ===== Fade in ===== */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ===== Card effect ===== */
.auth-box {
  background: rgba(255,255,255,0.75);
  padding: 45px 40px;
  border-radius: 28px;
  box-shadow: 0 10px 26px rgba(0,0,0,0.08);
  border: 1px solid rgba(255,255,255,0.6);
  backdrop-filter: blur(14px);
  animation: fadeInUp .6s ease;
}

/* ===== Input ===== */
.form-control-lg {
  border-radius: 14px;
  padding: 12px 16px;
}
.form-control-lg:focus {
  box-shadow: 0 0 0 4px rgba(0,150,136,0.25);
}

/* ===== Button ocean ===== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  background-size: 250% 250%;
  border: none;
  color: white !important;
  font-weight: 700;
  border-radius: 14px;
  padding: 12px;
  transition: .3s ease;
}
.btn-ocean:hover {
  background-position: right;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,150,136,0.35);
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<section style="max-width:480px; margin:auto;">
  <div class="auth-box fade-in">

    <div class="text-center mb-4">
      <h2 class="fw-bold text-primary mb-2">Đăng nhập hệ thống</h2>
      <p class="text-muted">Chào mừng bạn quay lại với <strong>AquaShop</strong></p>
    </div>

    <form method="POST" action="{{ route('login.process') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Tên đăng nhập hoặc Email</label>
        <input type="text" name="username"
               class="form-control form-control-lg"
               placeholder="Nhập tên đăng nhập hoặc email..."
               required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Mật khẩu</label>
        <input type="password" name="password"
               class="form-control form-control-lg"
               placeholder="••••••••" required>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <label class="form-check-label small text-muted">
          <input class="form-check-input me-1" type="checkbox" id="rememberMe"> Ghi nhớ đăng nhập
        </label>
        <a href="{{ route('password.forgot') }}" class="small text-decoration-none text-primary">
          Quên mật khẩu?
        </a>
      </div>

      <button class="btn btn-ocean w-100 mt-1">
        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
      </button>
    </form>

    <p class="mt-4 text-center text-muted">
      Chưa có tài khoản?
      <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none">
        Đăng ký ngay
      </a>
    </p>

  </div>
</section>

@include('layouts.footer')

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelector(".fade-in").classList.add("visible");
});
</script>

@endsection
