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

/* ---------- FILTER BOX ---------- */
.filter-box {
  background: linear-gradient(145deg, #f9fbff, #eef2f7);
  border: 1px solid #e1e5eb;
  padding: 16px;
  border-radius: 16px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.06);
  transition: .3s;
}
.filter-box:hover {
  box-shadow: 0 10px 28px rgba(0,0,0,0.08);
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

</style>


<h2 class="fw-bold mb-4">
  🛒 Khám phá sản phẩm AquaShop
</h2>


<!-- ===================== FILTER ===================== -->
<form class="row g-2 mb-4 filter-box" method="GET" action="{{ route('products.index') }}">

  <div class="col-lg-3 col-sm-6">
    <input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="🔍 Tìm tên sản phẩm...">
  </div>

  <div class="col-lg-2 col-sm-6">
    <select class="form-select" name="category">
      <option value="">Danh mục</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @if($category==$c->id) selected @endif>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-lg-2 col-sm-6">
    <select class="form-select" name="brand">
      <option value="">Thương hiệu</option>
      @foreach($brands as $b)
        <option value="{{ $b->id }}" @if($brand==$b->id) selected @endif>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-lg-2 col-sm-6">
    <input class="form-control" type="number" step="1000" name="min_price" value="{{ $min }}" placeholder="Giá từ">
  </div>

  <div class="col-lg-2 col-sm-6">
    <input class="form-control" type="number" step="1000" name="max_price" value="{{ $max }}" placeholder="Đến">
  </div>

  <div class="col-lg-1 col-sm-12 d-grid">
    <button class="btn btn-ocean">Lọc</button>
  </div>

  <div class="col-12 text-end mt-1">
    <a class="small text-muted" href="{{ route('products.index') }}">Xóa lọc</a>
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
