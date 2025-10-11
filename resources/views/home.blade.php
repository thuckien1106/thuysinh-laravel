@extends('layouts.header')
@section('title', 'Trang chủ')
@section('content')

<section class="hero-ocean mb-5">
  <div class="text">
    <h1>Thế giới thủy sinh ngay trong ngôi nhà bạn</h1>
    <p>Cây – cá – phụ kiện chất lượng, giao nhanh, hướng dẫn tận tâm.  
    Mang lại không gian xanh – thư giãn giữa cuộc sống hiện đại 🌿</p>
    <a href="#products" class="btn btn-ocean">Khám phá ngay</a>
  </div>
  <img src="{{ asset('assets/img/hero_fish.jpg') }}" alt="AquaShop Hero">
</section>

<h2 id="products" class="fw-semibold mb-4 text-center">✨ Sản phẩm nổi bật ✨</h2>

<div class="row g-4">
  @foreach($products as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card product-card h-100">
      <img src="{{ asset('assets/img/products/'.$p->image) }}" class="card-img-top product-thumb" alt="{{ $p->name }}">
      <div class="card-body text-center">
        <h6 class="card-title mb-1">{{ $p->name }}</h6>
        <div class="text-primary fw-semibold mb-2">{{ number_format($p->price, 0, ',', '.') }} đ</div>
        <a href="{{ route('product.show', $p->id) }}" class="btn btn-outline-ocean btn-sm">Xem chi tiết</a>
      </div>
    </div>
  </div>
  @endforeach
</div>

@include('layouts.footer')
@endsection
