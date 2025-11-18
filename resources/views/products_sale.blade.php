@extends('layouts.header')
@section('title', 'Sản phẩm giảm giá')

@section('breadcrumb')
  @include('partials.breadcrumb', [
    'items' => [
      ['label' => 'Trang chủ', 'url' => route('home')],
      ['label' => 'Giảm giá']
    ]
  ])
@endsection

@section('content')

<style>
/* ====================== APPEAR ANIMATION ====================== */
.sale-appear {
  opacity: 0;
  transform: translateY(22px) scale(.98);
  transition: .6s ease;
}
.sale-appear.visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.sale-delay-1 { transition-delay: .05s; }
.sale-delay-2 { transition-delay: .1s; }
.sale-delay-3 { transition-delay: .15s; }
.sale-delay-4 { transition-delay: .2s; }

/* ====================== PRODUCT CARD ====================== */
.sale-card {
  border: none;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  background: white;
  transition: .25s ease;
}
.sale-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

/* IMAGE */
.sale-thumb {
  height: 220px;
  object-fit: cover;
  transition: .35s ease;
}
.sale-card:hover .sale-thumb {
  transform: scale(1.07);
}

/* SALE BADGE */
.badge-sale {
  background: linear-gradient(90deg,#ef4444,#f97316);
  padding: 6px 12px;
  border-radius: 40px;
  color: white;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(239,68,68,0.35);
}

/* PRICE */
.sale-final {
  color: #e63946;
  font-size: 17px;
  font-weight: 700;
}
.sale-original {
  text-decoration: line-through;
  color: #94a3b8;
  font-size: 13px;
}

/* MODAL STYLE */
.coupon-modal .modal-content {
  border-radius: 18px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.15);
}
.coupon-modal .modal-header {
  background: linear-gradient(90deg,#0b5ed7,#0ea5e9);
  color: #fff;
  border-radius: 18px 18px 0 0;
}
.coupon-desc {
  background: #fff7e6;
  border: 1px solid #ffe6c7;
  border-radius: 12px;
  padding: 12px;
}
.coupon-badge {
  background: white;
  border: 1px dashed #ffb74d;
  padding: 3px 6px;
  border-radius: 8px;
  font-weight: 600;
}
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  border-radius: 12px;
  color: white !important;
  font-weight: 700;
  transition: .25s;
}
.btn-ocean:hover {
  transform: translateY(-2px);
}
</style>

<h2 class="fw-bold mb-4 fade-in">🔥 Sản phẩm đang giảm giá</h2>

<!-- ========================= COUPON MODAL ========================= -->
@php 
  $uid = session('admin')->id ?? 'guest'; 
  $role = session('admin')->role ?? 'guest';
  $applied = session('coupon')['code'] ?? null;
@endphp

<div class="modal fade coupon-modal" id="couponModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-ticket-perforated me-2"></i>Ưu đãi dành cho bạn</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="coupon-desc mb-3">
          <div>Nhập mã <span class="coupon-badge">DATCUTELOVE</span> để giảm <b>15%</b> đơn hàng.</div>
          <small class="text-muted">Mỗi tài khoản được sử dụng 1 lần.</small>
        </div>

        <form method="POST" action="{{ route('coupon.save') }}" id="couponForm">
          @csrf
          <div class="input-group">
            <input name="code" value="datcutelove" class="form-control form-control-lg">
            <button class="btn btn-ocean">Lưu mã</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- ========================= PRODUCTS ========================= -->
<div class="row g-4">
@forelse($products as $p)
@php 
  $img = 'assets/img/products/'.$p->image; 
  $percent = optional($p->activeDiscount)->percent;
  $ver = file_exists(public_path($img)) ? '?v='.filemtime(public_path($img)) : '';
@endphp

<div class="col-6 col-md-4 col-lg-3 sale-appear sale-delay-{{ ($loop->index % 4) + 1 }}">
  <div class="sale-card">

    <!-- IMAGE -->
    <div class="position-relative">
      <a href="{{ route('product.show', $p->id) }}">
        <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}" 
             class="sale-thumb w-100">
      </a>

      @if($percent)
        <span class="badge-sale position-absolute" style="top:10px; right:10px">-{{ $percent }}%</span>
      @endif
    </div>

    <!-- INFO -->
    <div class="p-3">
      <div class="fw-semibold mb-1 text-dark" style="min-height:44px">{{ $p->name }}</div>

      <div class="d-flex align-items-baseline gap-2">
        <div class="sale-final">{{ number_format($p->final_price) }} đ</div>
        <div class="sale-original">{{ number_format($p->price) }} đ</div>
      </div>

      @php
        $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
        $cnt = \App\Models\Review::where('product_id',$p->id)->count();
        $sold = \DB::table('order_details as od')
            ->join('orders as o','o.id','=','od.order_id')
            ->where('od.product_id',$p->id)
            ->where('o.status','completed')
            ->sum('od.quantity');
        $rounded = (int)floor($avg + 0.5);
      @endphp

      <!-- Rating + Sold -->
      @if($cnt>0 || $sold>0)
      <div class="mt-1 small d-flex gap-2 align-items-center">
        @if($cnt>0)
          <span>
            @for($i=1;$i<=5;$i++)
              <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
            @endfor
            <span class="text-muted">{{ $avg }} ({{ $cnt }})</span>
          </span>
        @endif

        @if($sold>0)
          <span class="text-success"><i class="bi bi-bag-check"></i> {{ number_format($sold) }}</span>
        @endif
      </div>
      @endif
    </div>

    <a href="{{ route('product.show', $p->id) }}" class="stretched-link"></a>
  </div>
</div>

@empty
  <div class="col-12 text-center text-muted py-5">Hiện chưa có sản phẩm giảm giá.</div>
@endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4">{{ $products->links() }}</div>

@include('layouts.footer')

<!-- ========================= JS ========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  /* APPEAR EFFECT */
  document.querySelectorAll(".sale-appear").forEach(el => {
    setTimeout(() => el.classList.add("visible"), 150);
  });

  /* MODAL LOGIC */
  var uid = '{{ $uid }}';
  var role = '{{ $role }}';
  var applied = '{{ strtoupper($applied ?? "") }}';

  if (uid !== 'guest' && role === 'user') {
    if (applied !== 'DATCUTELOVE') {
      let shownKey = 'saleModal_' + uid;
      if (!sessionStorage.getItem(shownKey)) {
        let m = document.getElementById('couponModal');
        bootstrap.Modal.getOrCreateInstance(m).show();
        sessionStorage.setItem(shownKey, '1');
      }
    }
  }

});
</script>

@endsection
