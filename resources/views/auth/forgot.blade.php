@extends('layouts.header')
@section('title','Quên mật khẩu')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm" style="max-width:520px;margin:auto;">
  <h3 class="fw-bold mb-3 text-primary">Quên mật khẩu</h3>
  <p class="text-muted">Nhập email hoặc tên đăng nhập để nhận mã xác minh (demo sẽ hiển thị mã ngay trên màn hình).</p>


  <form method="POST" action="{{ route('password.forgot.submit') }}" class="mt-3">
    @csrf
    <div class="mb-3">
      <label class="form-label">Email hoặc tên đăng nhập</label>
      <input type="text" class="form-control" name="identifier" required>
    </div>
    <button class="btn btn-ocean">Gửi mã</button>
    <a href="{{ route('login') }}" class="btn btn-link">Trở lại đăng nhập</a>
  </form>
</section>

@endsection
