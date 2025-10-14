@extends('layouts.header')
@section('title','Đặt lại mật khẩu')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm" style="max-width:520px;margin:auto;">
  <h3 class="fw-bold mb-3 text-primary">Đặt lại mật khẩu</h3>
  <p class="text-muted">Nhập mã xác minh 6 chữ số và mật khẩu mới.</p>


  <form method="POST" action="{{ route('password.reset.submit') }}" class="mt-3">
    @csrf
    <div class="mb-3">
      <label class="form-label">Mã xác minh</label>
      <input type="text" class="form-control" name="code" maxlength="6" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mật khẩu mới</label>
      <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Xác nhận mật khẩu</label>
      <input type="password" class="form-control" name="password_confirmation" required>
    </div>
    <button class="btn btn-ocean">Đặt lại mật khẩu</button>
    <a href="{{ route('login') }}" class="btn btn-link">Trở lại đăng nhập</a>
  </form>
</section>

@endsection
