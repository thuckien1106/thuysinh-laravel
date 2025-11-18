@extends('layouts.header')
@section('title', 'Đăng ký')
@section('content')

<style>
/* Reuse same style as login */
.auth-box {
  background: rgba(255,255,255,0.75);
  padding: 45px 40px;
  border-radius: 28px;
  box-shadow: 0 10px 26px rgba(0,0,0,0.08);
  border: 1px solid rgba(255,255,255,0.6);
  backdrop-filter: blur(14px);
  animation: fadeInUp .6s ease;
}
.form-control-lg {
  border-radius: 14px;
  padding: 12px 16px;
}
.form-control-lg:focus {
  box-shadow: 0 0 0 4px rgba(0,150,136,0.25);
}
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
.fade-in {
  opacity: 0; transform: translateY(20px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1; transform: translateY(0);
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<section style="max-width:480px; margin:auto;">
  <div class="auth-box fade-in">

    <h2 class="fw-bold text-center text-primary mb-4">Tạo tài khoản mới</h2>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.process') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Tên đăng nhập</label>
        <input type="text" name="username" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Mật khẩu</label>
        <input type="password" name="password" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
      </div>

      <button class="btn btn-ocean w-100 mt-3">Đăng ký</button>
    </form>

    <p class="mt-4 text-center text-muted">
      Đã có tài khoản?
      <a href="{{ route('login.form') }}" class="fw-semibold text-primary text-decoration-none">
        Đăng nhập
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
