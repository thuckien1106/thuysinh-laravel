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
/* ============================================================
   🔥 PREMUIUM SALE PAGE – AQUASHOP STYLE
   Đồng nhất với trang danh sách Sản phẩm
   ============================================================ */

/* ---------- APPEAR ANIMATION ---------- */
.sale-appear {
  opacity: 0;
  transform: translateY(26px) scale(.96);
  transition: .65s ease;
}
.sale-appear.visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.sale-delay-1 { transition-delay: .1s }
.sale-delay-2 { transition-delay: .2s }
.sale-delay-3 { transition-delay: .3s }
.sale-delay-4 { transition-delay: .4s }

/* ---------- SALE CARD ---------- */
.sale-card {
  border: none;
  overflow: hidden;
  border-radius: 18px;
  background: #ffffff;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  transition: 0.35s ease;
}
.sale-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 14px 30px rgba(0,0,0,0.14);
}

/* IMAGE */
.sale-thumb {
  height: 230px;
  object-fit: cover;
  border-radius: 14px;
  transition: .45s ease;
}
.sale-card:hover .sale-thumb {
  transform: scale(1.08);
  filter: brightness(1.06);
}

/* ---------- BADGE SALE ---------- */
.badge-sale {
  background: linear-gradient(90deg,#ff2d55,#ff6b6b);
  padding: 7px 14px;
  border-radius: 40px;
  color: white;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(255,45,85,0.35);
}

/* ---------- PRICE ---------- */
.sale-final {
  background: linear-gradient(90deg, #e63946, #ff5f5f);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
  font-size: 17px;
}
.sale-original {
  text-decoration: line-through;
  color: #94a3b8;
  font-size: 12px;
}

/* ---------- MODAL STYLE (upgrade) ---------- */
.coupon-modal .modal-content {
  border-radius: 20px;
  border: none;
  box-shadow: 0 12px 38px rgba(0,0,0,0.18);
}
.coupon-modal .modal-header {
  background: linear-gradient(90deg,#009688,#00a8a8);
  color: #fff;
  border-radius: 20px 20px 0 0;
}
.coupon-desc {
  background: #fff7e6;
  border-radius: 12px;
  border-left: 4px solid #ffb74d;
  padding: 12px 14px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}
.coupon-badge {
  background: white;
  border: 1px dashed #ffb74d;
  padding: 4px 8px;
  border-radius: 10px;
  font-weight: 700;
  color: #ff9800;
}

/* ---------- Ocean Button (same as product page) ---------- */
.btn-ocean {
  background: linear-gradient(90deg, #009688, #00bfa5, #00a08a);
  background-size: 200% 200%;
  color: white !important;
  border: none;
  padding: 10px 22px;
  border-radius: 14px;
  font-weight: 700;
  font-size: 15px;
  transition: 0.35s ease;
  box-shadow: 0 6px 18px rgba(0, 150, 136, 0.28);
}
.btn-ocean:hover {
  background-position: 100% 0;
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(0, 150, 136, 0.38);
}
</style>

<h2 class="fw-bold mb-4">🔥 Sản phẩm đang giảm giá</h2>

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
          Nhập mã <span class="coupon-badge">DATCUTELOVE</span> để giảm <b>15%</b> đơn hàng.<br>
          <small class="text-muted">Mỗi tài khoản được sử dụng 1 lần.</small>
        </div>

        <form method="POST" action="{{ route('coupon.save') }}">
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

    <div class="position-relative">
      <a href="{{ route('product.show', $p->id) }}">
        <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}" 
             class="sale-thumb w-100">
      </a>

      @if($percent)
        <span class="badge-sale position-absolute" style="top:10px; right:10px">-{{ $percent }}%</span>
      @endif
    </div>

    <div class="p-3">
      <div class="fw-semibold mb-1 text-dark" style="min-height:44px">{{ $p->name }}</div>

      <div class="d-flex align-items-baseline gap-2 mb-1">
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

      @if($cnt>0 || $sold>0)
      <div class="mt-2 small d-flex gap-2 align-items-center">
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

<div class="mt-4">{{ $products->links() }}</div>

@include('layouts.footer')

<script>
document.addEventListener("DOMContentLoaded", function () {

  /* appear */
  document.querySelectorAll(".sale-appear").forEach(el => {
    setTimeout(() => el.classList.add("visible"), 130);
  });

  /* modal logic */
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
