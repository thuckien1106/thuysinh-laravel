@extends('layouts.header')
@section('title', 'Đăng nhập')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm" style="max-width: 480px; margin:auto;">
  <h2 class="fw-bold text-center text-primary mb-4">Đăng nhập tài khoản</h2>
  <form>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" class="form-control form-control-lg" placeholder="email@example.com">
    </div>
    <div class="mb-3">
      <label>Mật khẩu</label>
      <input type="password" class="form-control form-control-lg" placeholder="********">
    </div>
    <button class="btn btn-ocean w-100 mt-3">Đăng nhập</button>
  </form>
  <p class="mt-4 text-center">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
</section>

@include('layouts.footer')
@endsection
