@extends('layouts.header')
@section('title', 'Đăng nhập')
@section('content')

<section class="p-5 bg-light rounded-4 shadow-sm" style="max-width:480px; margin:auto;">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">Đăng nhập hệ thống</h2>
    <p class="text-muted">Chào mừng bạn quay lại với <strong>AquaShop</strong></p>
  </div>

  {{-- Thông báo thành công --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Thông báo lỗi --}}
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ $errors->first() }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('login.process') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label fw-semibold">Tên đăng nhập hoặc Email</label>
      <input type="text" name="username" class="form-control form-control-lg" placeholder="Nhập tên đăng nhập hoặc email..." required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Mật khẩu</label>
      <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="rememberMe">
        <label class="form-check-label small text-muted" for="rememberMe">Ghi nhớ đăng nhập</label>
      </div>
      <a href="#" class="text-decoration-none small text-primary">Quên mật khẩu?</a>
    </div>

    <button class="btn btn-primary w-100 py-2 fw-semibold">
      <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
    </button>
  </form>

  <p class="mt-4 text-center text-muted">
    Chưa có tài khoản?
    <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none">
      Đăng ký ngay
    </a>
  </p>
</section>

@include('layouts.footer')
@endsection
