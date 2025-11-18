@extends('layouts.header')
@section('title', $product->name)

@section('breadcrumb')
  @include('partials.breadcrumb', [
    'items' => [
      ['label' => 'Trang chủ', 'url' => route('home')],
      ['label' => 'Sản phẩm', 'url' => route('products.index')],
      ['label' => $product->name]
    ]
  ])
@endsection

@section('content')

<style>
/* ===================== GLOBAL ANIMATIONS ===================== */
.fade-in {
  opacity: 0;
  transform: translateY(16px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ===================== PRODUCT IMAGE ===================== */
.product-main-img {
  border-radius: 16px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.12);
  transition: .35s ease;
}
.product-main-img:hover {
  transform: scale(1.03);
}

.thumb-list img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
  cursor: pointer;
  border: 2px solid transparent;
  transition: .25s ease;
}
.thumb-list img:hover {
  transform: scale(1.06);
  border-color: #00bfa5;
}

/* ===================== BADGES ===================== */
.badge-sale {
  background: linear-gradient(90deg,#ef4444,#f97316);
  color: #fff;
  padding: 6px 14px;
  border-radius: 30px;
  font-weight: 600;
  box-shadow: 0 5px 14px rgba(239,68,68,.35);
}

.badge-top {
  background: #ffe46f;
  color: #5b4200;
  padding: 6px 14px;
  border-radius: 30px;
  font-weight: 700;
}

/* ===================== PRICE ===================== */
.price-final {
  color: #e63946;
  font-size: 28px;
  font-weight: 800;
}
.price-original {
  text-decoration: line-through;
  font-size: 16px;
  color: #9e9e9e;
}

/* ===================== ADD TO CART ===================== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  border: none;
  color: white !important;
  font-weight: 700;
  border-radius: 14px;
  padding: 12px 26px;
  transition: .35s;
}
.btn-ocean:hover {
  background-position: right;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0,150,136,0.35);
}

/* ===================== INFO BOXES ===================== */
.info-box {
  background: #f8fafc;
  border-radius: 16px;
  padding: 20px;
  border: 1px solid #e6ecf1;
}

/* ===================== REVIEW CARD ===================== */
.review-card {
  border-radius: 12px;
  background: #fdfdfd;
  border: 1px solid #eee;
  padding: 14px 18px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}
</style>

<!-- ===================== PRODUCT DETAIL ===================== -->
<div class="row g-4 fade-in">

  <!-- ================= IMAGE COLUMN ================= -->
  <div class="col-md-6">
    <div class="position-relative mb-3">
      @php
        $img = 'assets/img/products/'.$product->image;
        $ver = file_exists(public_path($img)) ? '?v='.filemtime(public_path($img)) : '';
      @endphp

      <img id="productMainImage"
           src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}"
           class="img-fluid w-100 product-main-img"
           alt="{{ $product->name }}">

      @if(($isTop ?? false))
        <span class="badge-top position-absolute" style="top:10px; left:10px">🔥 Top</span>
      @endif

      @php $percent = optional($product->activeDiscount)->percent; @endphp
      @if($percent)
        <span class="badge-sale position-absolute" style="top:10px; right:10px">-{{ $percent }}%</span>
      @endif
    </div>
  </div>

  <!-- ================= INFO COLUMN ================= -->
  <div class="col-md-6">
    <h2 class="fw-bold">{{ $product->name }}</h2>

    {{-- PRICE --}}
    @if($percent)
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="price-final">{{ number_format($product->final_price) }} đ</div>
        <div class="price-original">{{ number_format($product->price) }} đ</div>
      </div>
    @else
      <h4 class="fw-bold text-primary">{{ number_format($product->price) }} đ</h4>
    @endif

    {{-- RATING & SOLD --}}
    @if(($reviewCount ?? 0) > 0 || ($soldCount ?? 0) > 0)
      <div class="mt-2 d-flex align-items-center gap-3">
        @if($reviewCount > 0)
          <div class="text-warning">
            @php $rounded = floor($avgRating + 0.5); @endphp
            @for($i=1;$i<=5;$i++)
              <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
            @endfor
            <span class="text-muted small">{{ number_format($avgRating,1) }} ({{ $reviewCount }})</span>
          </div>
        @endif

        @if($soldCount > 0)
          <div class="text-success small"><i class="bi bi-bag-check me-1"></i>Đã bán {{ number_format($soldCount) }}</div>
        @endif
      </div>
    @endif

    {{-- SHORT DESCRIPTION --}}
    <p class="text-secondary mt-3">
      {{ str_replace('\\n', "\n", $product->short_description ?: $product->description) }}
    </p>

    <!-- ADD TO CART -->
    <div class="mt-4 d-flex align-items-center gap-3">
      <form method="POST" action="{{ route('cart.add') }}" id="addToCartForm" class="d-flex align-items-center gap-2">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="input-group" style="width:150px;">
          <button class="btn btn-outline-secondary" type="button" id="qtyMinus">–</button>
          <input type="number" id="qtyInput" name="quantity" class="form-control text-center" value="1" min="1">
          <button class="btn btn-outline-secondary" type="button" id="qtyPlus">+</button>
        </div>

        <button type="submit" class="btn btn-ocean px-4">Thêm vào giỏ</button>
      </form>
    </div>

    <!-- PRODUCT DETAILS -->
    <div class="mt-4">
      <h5 class="fw-bold mb-2">Chi tiết sản phẩm</h5>
      <div class="info-box mb-3">
        {!! nl2br(e(str_replace('\\n', "\n", $product->long_description ?: $product->description))) !!}
      </div>

      <div class="info-box mb-3">
        <h6 class="fw-semibold mb-1">Thông số kỹ thuật</h6>
        <div class="small">{!! nl2br(e($product->specs ?: 'Đang cập nhật')) !!}</div>
      </div>

      <div class="info-box">
        <h6 class="fw-semibold mb-1">Hướng dẫn chăm sóc</h6>
        <div class="small">{!! nl2br(e($product->care_guide ?: 'Đang cập nhật')) !!}</div>
      </div>
    </div>

  </div>
</div>

<!-- ================= REVIEWS ================= -->
<hr class="my-4 fade-in">

<h4 id="review" class="fw-bold fade-in">Đánh giá sản phẩm</h4>

@if(session('success'))
  <div class="alert alert-success fade-in">{{ session('success') }}</div>
@endif

<div class="fade-in">
  @forelse($reviews as $r)
    <div class="review-card mb-3">
      <div class="small text-muted">{{ $r->created_at }}</div>
      <div class="fw-bold text-warning mb-1">
        {{ str_repeat('★', (int)$r->rating) }}{{ str_repeat('☆', 5-(int)$r->rating) }}
      </div>
      <div>{{ $r->content }}</div>
    </div>
  @empty
    <div class="text-muted">Chưa có đánh giá nào.</div>
  @endforelse
</div>

@include('layouts.footer')
@endsection

<!-- ================= JS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function(){

  // Fade-in animation
  document.querySelectorAll(".fade-in").forEach(el=>{
    setTimeout(()=> el.classList.add("visible"), 150);
  });

  // Qty handler
  const qty = document.getElementById('qtyInput');
  document.getElementById('qtyMinus').onclick = () => qty.stepDown();
  document.getElementById('qtyPlus').onclick = () => qty.stepUp();

  // Add to cart Ajax + Mini cart
  const form = document.getElementById('addToCartForm');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    let fd = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(() => fetch('{{ route('cart.mini') }}'))
    .then(r => r.json())
    .then(data => {
      document.getElementById('miniCartBody').innerHTML = data.html;
      if (typeof updateCartBadge === 'function') updateCartBadge(data.count);
      bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('cartOffcanvas')).show();
    })
    .catch(()=> form.submit());
  });
});
</script>
