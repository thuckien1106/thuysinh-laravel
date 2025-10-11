@extends('layouts.header')
@section('title', 'Đăng ký')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm" style="max-width:480px; margin:auto;">
  <h2 class="fw-bold text-center text-primary mb-4">Tạo tài khoản mới</h2>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('register.process') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Tên đăng nhập</label>
      <input type="text" name="username" class="form-control form-control-lg" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control form-control-lg" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mật khẩu</label>
      <input type="password" name="password" class="form-control form-control-lg" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Xác nhận mật khẩu</label>
      <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
    </div>
    <button class="btn btn-ocean w-100 mt-3">Đăng ký</button>
  </form>

  <p class="mt-4 text-center">Đã có tài khoản? <a href="{{ route('login.form') }}">Đăng nhập</a></p>
</section>

@include('layouts.footer')
@endsection
