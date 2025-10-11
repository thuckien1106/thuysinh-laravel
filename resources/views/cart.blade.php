@extends('layouts.header')
@section('title', 'Giỏ hàng')
@section('content')

<h2 class="fw-bold text-primary mb-4">🛒 Giỏ hàng của bạn</h2>

<div class="card shadow-sm rounded-4 border-0">
  <div class="card-body text-center py-5">
    <img src="{{ asset('assets/img/empty_cart.webp') }}" alt="Empty Cart" width="160" class="mb-3">
    <p class="text-secondary mb-3">Hiện tại bạn chưa có sản phẩm nào trong giỏ hàng.</p>
    <a href="{{ route('home') }}" class="btn btn-ocean">Tiếp tục mua sắm</a>
  </div>
</div>

@include('layouts.footer')
@endsection
