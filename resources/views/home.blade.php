@extends('layouts.header')
@section('title', 'Trang chủ')

@section('content')

<style>
/* ==================== GLOBAL SMOOTH ANIMATION ==================== */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ==================== HERO SECTION ==================== */
.hero-ocean {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 40px;
  padding: 60px 40px;
  border-radius: 22px;
  background: linear-gradient(135deg, rgba(0,150,136,0.2), rgba(0,150,136,0.05));
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.35);
  box-shadow: 0 15px 35px rgba(0,0,0,0.07);
}

.hero-ocean img {
  width: 48%;
  border-radius: 20px;
  box-shadow: 0 10px 28px rgba(0,0,0,0.18);
  transition: 0.4s ease;
}
.hero-ocean img:hover {
  transform: scale(1.04);
}

.hero-title {
  font-family: 'Poppins', sans-serif;
  font-size: 54px;
  font-weight: 700;
  line-height: 1.1;
  background: linear-gradient(90deg, #009688, #00bfa5, #009688);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-size: 200% 200%;
  animation: heroGradient 6s ease infinite, fadeUp 1.2s ease forwards;
  text-shadow: 0 0 25px rgba(0,255,204,0.3);
}
@keyframes heroGradient {
  0% { background-position: 0% 50% }
  50% { background-position: 100% 50% }
  100% { background-position: 0% 50% }
}

/* ==================== BUTTON ==================== */
.btn-ocean {
  background: linear-gradient(90deg, #009688, #00bfa5);
  color:white;
  padding: 13px 32px;
  font-size: 16px;
  border-radius: 50px;
  transition: 0.25s ease;
  border: none;
  font-weight: 600;
}
.btn-ocean:hover {
  transform: translateY(-4px);
  background: linear-gradient(90deg, #00bfa5, #009688);
}

/* ==================== TITLES ==================== */
.section-title {
  font-size: 30px;
  font-weight: 700;
  margin: 40px 0 15px;
  background: linear-gradient(90deg,#00bfa5,#00796b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ==================== PRODUCT CARDS ==================== */
.product-card {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  background: white;
  box-shadow: 0 8px 30px rgba(0,0,0,0.07);
  transition: 0.3s ease;
}
.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}
.product-thumb {
  height: 230px;
  width: 100%;
  object-fit: cover;
  border-radius: 18px;
  transition: 0.35s ease-in-out;
}
.product-card:hover .product-thumb {
  transform: scale(1.06);
}

/* BEAUTIFUL BADGES */
.badge-top {
  background: #ffe066;
  color: #6d5500;
  padding: 5px 12px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
}
.badge-sale {
  background: #ff3d3d;
  padding: 6px 10px;
  color: white;
  border-radius: 12px;
  font-size: 0.82rem;
  font-weight: 600;
}

/* PRICE */
.price-final {
  font-size: 18px;
  font-weight: 700;
  color: #e53935;
}
.price-original {
  font-size: 14px;
}

/* TEXT */
.card-title {
  font-weight: 600;
  font-size: 15.5px;
  min-height: 42px;
}

</style>

{{-- ================= HERO ================= --}}
<section class="hero-ocean mb-5 fade-in">
  <div class="text">
      <h1 class="hero-title">Thế giới thủy sinh ngay trong ngôi nhà bạn</h1>
      <p class="fade-in">Cây, cá cảnh và phụ kiện chất lượng, giao nhanh, hướng dẫn tận tâm.
      Mang lại không gian xanh thư giãn giữa cuộc sống hiện đại.</p>
      <a href="#products" class="btn btn-ocean fade-in">Khám phá ngay</a>
  </div>
  <img src="{{ asset('assets/img/hero_fish.jpg') }}" alt="AquaShop Hero">
</section>

<script>
// smooth scroll
document.addEventListener('DOMContentLoaded', function(){
  const items = document.querySelectorAll('.fade-in');
  const observer = new IntersectionObserver(entries=>{
    entries.forEach(e=>{
      if(e.isIntersecting){ e.target.classList.add('visible'); }
    });
  }, { threshold: 0.12 });
  items.forEach(i=>observer.observe(i));
});
</script>

{{-- ================= PRODUCT SECTIONS ================= --}}
<h2 id="products" class="section-title fade-in">SẢN PHẨM NỔI BẬT</h2>

<div class="row g-4 mb-4">
@foreach(($featuredProducts ?? collect()) as $p)
<div class="col-6 col-md-4 col-lg-3 fade-in">
  <div class="product-card p-2 h-100">

    @php 
      $img = 'assets/img/products/'.$p->image; 
      $isTop = in_array($p->id, $topIds ?? []); 
      $percent = optional($p->activeDiscount)->percent; 
      $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : ''; 
    @endphp

    <div class="position-relative">
      <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}"
           class="product-thumb" alt="{{ $p->name }}">
      @if($isTop)
        <span class="badge-top position-absolute" style="top:10px; left:10px;">🔥 Top</span>
      @endif
      @if($percent)
        <span class="badge-sale position-absolute" style="top:10px; right:10px;">-{{ $percent }}%</span>
      @endif
    </div>

    <div class="p-2 text-center">
      <div class="card-title">{{ $p->name }}</div>

      @if($percent)
      <div class="d-flex justify-content-center gap-2 align-items-baseline">
        <span class="price-final">{{ number_format($p->final_price) }} đ</span>
        <span class="text-muted text-decoration-line-through price-original">{{ number_format($p->price) }} đ</span>
      </div>
      @else
        <span class="text-primary fw-bold">{{ number_format($p->price) }} đ</span>
      @endif

      {{-- RATING --}}
      @php
        $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
        $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
        $sold = (int)\DB::table('order_details as od')
          ->join('orders as o','o.id','=','od.order_id')
          ->where('od.product_id',$p->id)
          ->where('o.status','completed')
          ->sum('od.quantity');
        $rounded = (int)floor($avg + 0.5);
      @endphp

      <div class="mt-2 small">
        @if($cnt>0)
          @for($i=1;$i<=5;$i++)
            <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
          @endfor
          <span class="text-muted">{{ $avg }} ({{ $cnt }})</span>
        @endif

        @if($sold>0)
          <div class="text-success"><i class="bi bi-bag-check"></i> Đã bán {{ number_format($sold) }}</div>
        @endif
      </div>

      <a href="{{ route('product.show',$p->id) }}" class="btn btn-outline-ocean btn-sm mt-2">Xem chi tiết</a>
    </div>

  </div>
</div>
@endforeach
</div>

{{-- ================= OTHER ================= --}}
<h2 class="section-title fade-in">SẢN PHẨM KHÁC</h2>

<div class="row g-4 mb-4">
@foreach(($otherProducts ?? collect()) as $p)
{{-- card giống phía trên, giữ nguyên --}}
<div class="col-6 col-md-4 col-lg-3 fade-in">
  <div class="product-card p-2 h-100">

    @php 
      $img = 'assets/img/products/'.$p->image;
      $isTop = in_array($p->id, $topIds ?? []); 
      $percent = optional($p->activeDiscount)->percent; 
      $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : ''; 
    @endphp

    <div class="position-relative">
      <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}"
           class="product-thumb" alt="{{ $p->name }}">
      @if($isTop)
        <span class="badge-top position-absolute" style="top:10px; left:10px;">🔥 Top</span>
      @endif
      @if($percent)
        <span class="badge-sale position-absolute" style="top:10px; right:10px;">-{{ $percent }}%</span>
      @endif
    </div>

    <div class="p-2 text-center">
      <div class="card-title">{{ $p->name }}</div>

      @if($percent)
      <div class="d-flex justify-content-center gap-2 align-items-baseline">
        <span class="price-final">{{ number_format($p->final_price) }} đ</span>
        <span class="text-muted text-decoration-line-through price-original">{{ number_format($p->price) }} đ</span>
      </div>
      @else
        <span class="text-primary fw-bold">{{ number_format($p->price) }} đ</span>
      @endif

      {{-- RATING --}}
      @php
        $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
        $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
        $sold = (int)\DB::table('order_details as od')
          ->join('orders as o','o.id','=','od.order_id')
          ->where('od.product_id',$p->id)
          ->where('o.status','completed')
          ->sum('od.quantity');
        $rounded = (int)floor($avg + 0.5);
      @endphp

      <div class="mt-2 small">
        @if($cnt>0)
          @for($i=1;$i<=5;$i++)
            <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
          @endfor
          <span class="text-muted">{{ $avg }} ({{ $cnt }})</span>
        @endif

        @if($sold>0)
          <div class="text-success"><i class="bi bi-bag-check"></i> Đã bán {{ number_format($sold) }}</div>
        @endif
      </div>

      <a href="{{ route('product.show',$p->id) }}" class="btn btn-outline-ocean btn-sm mt-2">Xem chi tiết</a>
    </div>

  </div>
</div>
@endforeach
</div>

<div class="text-center mt-4 fade-in">
  <a href="{{ route('products.index') }}" class="btn btn-ocean px-4">Xem tất cả</a>
  <div class="small text-muted mt-1">Dùng thanh tìm kiếm để lọc chính xác hơn</div>
</div>

@include('layouts.footer')

@endsection
