@extends('layouts.header')
@section('title', 'Sản phẩm')

@section('breadcrumb')
  @include('partials.breadcrumb', [
    'items' => [
      ['label' => 'Trang chủ', 'url' => route('home')],
      ['label' => 'Sản phẩm']
    ]
  ])
@endsection

@section('content')

<style>
/* ============================================================
   🔥 PREMIUM PRODUCT PAGE STYLING – AQUASHOP
   ============================================================ */

/* ---------- PAGE TITLE ---------- */
.page-title-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 14px;
}
.page-title-chip {
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  background: rgba(0, 150, 136, 0.08);
  color: #00897b;
  font-weight: 600;
}

/* ---------- FILTER BOX ---------- */
.filter-box {
  background: radial-gradient(circle at top left, #f1f7ff 0, #eef2f7 45%, #ffffff 100%);
  border: 1px solid #e3e7ef;
  padding: 14px 18px;
  border-radius: 18px;
  box-shadow: 0 6px 20px rgba(13, 71, 161, 0.06);
  transition: 0.3s;
  position: relative;
  overflow: hidden;
}
.filter-box::before {
  content: "";
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 0% 0%, rgba(0, 188, 212, 0.09), transparent 55%);
  opacity: 0.8;
  pointer-events: none;
}
.filter-box-inner {
  position: relative;
  z-index: 2;
}
.filter-box:hover {
  box-shadow: 0 12px 32px rgba(13, 71, 161, 0.10);
  transform: translateY(-2px);
}

/* ---------- FILTER INPUT PILL ---------- */
.filter-pill {
  position: relative;
}
.filter-pill-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  color: #90a4ae;
  z-index: 2;
}
.filter-pill .filter-input,
.filter-pill .filter-select {
  border-radius: 999px;
  border: 1px solid #d5dde8;
  background-color: rgba(255, 255, 255, 0.9);
  padding-left: 40px;
  height: 44px;
  font-size: 14px;
  box-shadow: 0 2px 6px rgba(145, 158, 171, 0.12);
  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease,
    background-color 0.2s ease,
    transform 0.18s ease;
}
.filter-pill .filter-input::placeholder {
  color: #b0bec5;
  font-size: 13px;
}
.filter-pill .filter-input:focus,
.filter-pill .filter-select:focus {
  outline: none;
  border-color: #00acc1;
  box-shadow:
    0 0 0 1px rgba(0, 172, 193, 0.15),
    0 6px 18px rgba(0, 150, 136, 0.25);
  background-color: #ffffff;
  transform: translateY(-1px);
}

/* Button trong filter */
.filter-actions .btn-ocean {
  width: 100%;
  height: 44px;
  border-radius: 999px;
}

/* Link xoá lọc */
.filter-reset {
  font-size: 12px;
}

/* ---------- CARD WRAPPER ---------- */
.product-card {
  border: none;
  overflow: hidden;
  border-radius: 18px;
  background: #ffffff;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  transition: 0.35s ease;
  position: relative;
}
.product-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 12px 28px rgba(0,0,0,0.14);
}

/* ---------- PRODUCT IMAGE ---------- */
.product-thumb {
  border-radius: 14px;
  transition: 0.45s ease;
  object-fit: cover;
}
.product-card:hover .product-thumb {
  transform: scale(1.08);
  filter: brightness(1.05);
}

/* ---------- PRODUCT TITLE ---------- */
.product-name {
  font-size: 15px;
  font-weight: 600;
  height: 42px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* ---------- PRICE ---------- */
.price-final {
  background: linear-gradient(90deg, #e63946, #ff5f5f);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
  font-size: 17px;
}
.price-original {
  font-size: 13px;
}

/* ---------- BADGE SALE ---------- */
.badge-sale {
  background: linear-gradient(90deg, #ff2d55, #ff4545);
  color: #fff;
  padding: 6px 12px;
  border-radius: 30px;
  font-weight: 700;
  box-shadow: 0 4px 10px rgba(255,45,85,0.35);
}

/* ---------- BADGE TOP ---------- */
.badge-top {
  background: linear-gradient(90deg, #ffe56f, #ffbd2f);
  color: #5a4300;
  padding: 6px 12px;
  border-radius: 30px;
  font-weight: 700;
  box-shadow: 0 4px 10px rgba(255,200,0,0.35);
}

/* ---------- RATING ---------- */
.star-rating i {
  filter: drop-shadow(0 1px 3px rgba(255,193,7,0.35));
}

/* ---------- APPEAR ANIMATION ---------- */
.product-appear {
  opacity: 0;
  transform: translateY(30px) scale(0.95);
  transition: opacity .7s ease, transform .7s ease;
}
.product-appear.visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.product-delay-1 { transition-delay: .1s; }
.product-delay-2 { transition-delay: .2s; }
.product-delay-3 { transition-delay: .3s; }
.product-delay-4 { transition-delay: .4s; }

/* ======= PREMIUM OCEAN BUTTON ======= */
.btn-ocean {
  background: linear-gradient(90deg, #009688, #00bfa5, #00a08a);
  background-size: 200% 200%;
  color: white !important;
  border: none;
  padding: 10px 26px;
  border-radius: 14px;
  font-weight: 700;
  letter-spacing: 0.3px;
  font-size: 16px;
  transition: 0.35s ease;
  box-shadow: 0 6px 18px rgba(0, 150, 136, 0.28);
}
.btn-ocean:hover {
  background-position: 100% 0;
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(0, 150, 136, 0.38);
}
.btn-ocean:active {
  transform: scale(0.95);
}

/* Responsive tweak */
@media (max-width: 575.98px) {
  .filter-box {
    padding: 12px 12px;
  }
}
</style>


<div class="page-title-wrap">
  <h2 class="fw-bold mb-0">🛒 Khám phá sản phẩm AquaShop</h2>
  <span class="page-title-chip">Ưu đãi & sản phẩm mới</span>
</div>

<!-- ===================== FILTER ===================== -->
<form class="filter-box mb-4" method="GET" action="{{ route('products.index') }}">
  <div class="filter-box-inner">
    <div class="row g-2 align-items-center">

      <div class="col-lg-3 col-sm-6">
        <div class="filter-pill">
          <i class="bi bi-search filter-pill-icon"></i>
          <input
            class="form-control filter-input"
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Tìm tên sản phẩm, từ khóa..."
          >
        </div>
      </div>

      <div class="col-lg-2 col-sm-6">
        <div class="filter-pill">
          <i class="bi bi-grid filter-pill-icon"></i>
          <select class="form-select filter-select" name="category">
            <option value="">Danh mục</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @if($category==$c->id) selected @endif>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-lg-2 col-sm-6">
        <div class="filter-pill">
          <i class="bi bi-tags filter-pill-icon"></i>
          <select class="form-select filter-select" name="brand">
            <option value="">Thương hiệu</option>
            @foreach($brands as $b)
              <option value="{{ $b->id }}" @if($brand==$b->id) selected @endif>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-lg-2 col-sm-6">
        <div class="filter-pill">
          <i class="bi bi-cash-coin filter-pill-icon"></i>
          <input
            class="form-control filter-input"
            type="number"
            step="1000"
            name="min_price"
            value="{{ $min }}"
            placeholder="Giá từ"
          >
        </div>
      </div>

      <div class="col-lg-2 col-sm-6">
        <div class="filter-pill">
          <i class="bi bi-cash-coin filter-pill-icon"></i>
          <input
            class="form-control filter-input"
            type="number"
            step="1000"
            name="max_price"
            value="{{ $max }}"
            placeholder="Đến"
          >
        </div>
      </div>

      <div class="col-lg-1 col-sm-12 filter-actions">
        <button class="btn btn-ocean" type="submit">
          Lọc
        </button>
      </div>

      <div class="col-12 text-end mt-1">
        <a class="small text-muted filter-reset" href="{{ route('products.index') }}">
          Xóa lọc & xem tất cả
        </a>
      </div>
    </div>
  </div>
</form>


<!-- ===================== PRODUCT GRID ===================== -->
<div class="row g-4">

  @forelse($products as $p)
  <div class="col-6 col-md-4 col-lg-3 product-appear product-delay-{{ ($loop->index % 4) + 1 }}">

    <div class="card product-card h-100">

      @php 
        $img = 'assets/img/products/'.$p->image;
        $isTop = in_array($p->id, $topIds ?? []);
        $percent = optional($p->activeDiscount)->percent;
        $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : '';
      @endphp

      <div class="position-relative">
        <a href="{{ route('product.show', $p->id) }}">
          <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}"
               class="card-img-top product-thumb" height="230" alt="{{ $p->name }}">
        </a>

        @if($isTop)
          <span class="badge-top position-absolute" style="top:10px; left:10px;">🔥 Top</span>
        @endif

        @if($percent)
          <span class="badge-sale position-absolute" style="top:10px; right:10px;">-{{ $percent }}%</span>
        @endif
      </div>

      <div class="card-body">
        
        <div class="product-name">{{ $p->name }}</div>

        @if($percent)
        <div class="d-flex align-items-baseline gap-2 mt-1">
          <span class="price-final">{{ number_format($p->final_price) }} đ</span>
          <span class="text-muted text-decoration-line-through price-original">{{ number_format($p->price) }} đ</span>
        </div>
        @else
        <span class="text-primary fw-bold mt-1">{{ number_format($p->price) }} đ</span>
        @endif

        <!-- Rating & Sold -->
        @php
          $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
          $cnt = \App\Models\Review::where('product_id',$p->id)->count();
          $sold = \DB::table('order_details')->join('orders','orders.id','=','order_details.order_id')
                  ->where('order_details.product_id',$p->id)
                  ->where('orders.status','completed')->sum('order_details.quantity');
          $rounded = floor($avg + 0.5);
        @endphp

        @if($cnt>0 || $sold>0)
        <div class="mt-2 d-flex align-items-center justify-content-between small">
          @if($cnt>0)
            <div class="star-rating">
              @for($i=1;$i<=5;$i++)
                <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
              @endfor
              <span class="text-muted ms-1">{{ $avg }} ({{ $cnt }})</span>
            </div>
          @endif

          @if($sold>0)
            <div class="text-success">
              <i class="bi bi-bag-check-fill"></i> {{ number_format($sold) }}
            </div>
          @endif
        </div>
        @endif

        <a href="{{ route('product.show', $p->id) }}" class="stretched-link"></a>
      </div>
    </div>
  </div>

  @empty
    <div class="col-12 text-center text-muted">Không tìm thấy sản phẩm.</div>
  @endforelse

</div>

<!-- Pagination -->
<div class="mt-4">
  {{ $products->links() }}
</div>

@include('layouts.footer')

<!-- ===================== ANIMATION JS ===================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".product-appear");
  
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) e.target.classList.add("visible");
    });
  }, { threshold: 0.2 });

  cards.forEach(card => obs.observe(card));
});
</script>

@endsection
