@extends('layouts.header')
@section('title', $product->name)
@section('content')

<div class="row g-4">
  <div class="col-md-6">
    <div class="shadow-sm rounded-4 overflow-hidden">
      <img src="{{ asset('assets/img/products/'.$product->image) }}" class="img-fluid w-100" alt="{{ $product->name }}">
    </div>
  </div>
  <div class="col-md-6">
    <h2 class="fw-bold">{{ $product->name }}</h2>
    <h4 class="text-primary fw-semibold mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</h4>
    <p class="text-secondary">{{ $product->description }}</p>
    <div class="mt-4">
      <button class="btn btn-ocean me-2">Thêm vào giỏ hàng</button>
      <a href="{{ route('cart') }}" class="btn btn-outline-ocean">Xem giỏ hàng</a>
    </div>
  </div>
</div>

@include('layouts.footer')
@endsection
