@extends('layouts.header')
@section('title', 'Sản phẩm giảm giá')
@section('breadcrumb')
  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Giảm giá']
  ]])
@endsection
@section('content')

<h2 class="fw-bold mb-3">Sản phẩm đang giảm giá</h2>

<!-- Modal nhập mã giảm giá: chỉ hiện theo điều kiện -->
@php 
  $uid = session('admin')->id ?? 'guest'; 
  $applied = session('coupon')['code'] ?? null; 
  $role = session('admin')->role ?? 'guest';
@endphp
<style>
  .coupon-modal .modal-header{background:linear-gradient(90deg,#0b5ed7,#3aa0ff);color:#fff}
  .coupon-modal .modal-content{border-radius:16px}
  .coupon-modal .input-group .form-control{border-top-left-radius:12px;border-bottom-left-radius:12px}
  .coupon-modal .btn-ocean{border-radius:12px}
  .coupon-badge{display:inline-block;padding:.25rem .5rem;border-radius:8px;background:#fff;color:#d9480f;border:1px dashed #ffd8a8}
  .coupon-desc{background:#fff4e6;border:1px solid #ffe8cc;border-radius:12px;padding:.75rem}
  .product-thumb{height:200px;object-fit:cover}
  @media (max-width: 576px){ .product-thumb{height:170px} }
  </style>
<div class="modal fade coupon-modal" id="couponModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-fire me-2"></i>Ưu đãi dành riêng cho bạn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="coupon-desc mb-3">
          <div class="mb-1">Nhập mã <span class="coupon-badge">datcutelove</span> để giảm <strong>15%</strong> đơn hàng.</div>
          <div class="small text-muted">Mã áp dụng 1 lần cho mỗi tài khoản.</div>
        </div>
        <form method="POST" action="{{ route('coupon.save') }}" id="couponForm">
          @csrf
          <div class="input-group">
            <input type="text" name="code" class="form-control form-control-lg" placeholder="Nhập mã: datcutelove" value="datcutelove">
            <button class="btn btn-ocean">Lưu mã</button>
          </div>
        </form>
        <div class="form-text mt-2">Hộp thoại này hiển thị sau 10 phút và chỉ một lần cho mỗi tài khoản.</div>
      </div>
    </div>
  </div>
  </div>

<div class="row g-4">
  @forelse($products as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100">
      @php $img = 'assets/img/products/'.$p->image; $isTop = in_array($p->id, $topIds ?? []); $percent = optional($p->activeDiscount)->percent; $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : ''; @endphp
      <div class="position-relative">
        <a href="{{ route('product.show', $p->id) }}">
          <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}" class="card-img-top product-thumb" alt="{{ $p->name }}">
        </a>
        @if($isTop)
          <span class="badge bg-warning text-dark position-absolute" style="top:8px; left:8px;">Top</span>
        @endif
        @if($percent)
          <span class="badge badge-sale position-absolute" style="top:8px; right:8px;">-{{ $percent }}%</span>
        @endif
      </div>
      <div class="card-body position-relative">
        <div class="fw-semibold">{{ $p->name }}</div>
        @php $percent = optional($p->activeDiscount)->percent; @endphp
        <div class="d-flex align-items-baseline gap-2">
          <div class="text-danger fw-bold">{{ number_format($p->final_price, 0, ',', '.') }} đ</div>
          <div class="text-muted small text-decoration-line-through">{{ number_format($p->price, 0, ',', '.') }} đ</div>
          @if($percent)
            <span class="badge bg-danger-subtle text-danger border">-{{ $percent }}%</span>
          @endif
        </div>
        @php
          $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
          $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
          $sold = (int)\Illuminate\Support\Facades\DB::table('order_details as od')
            ->join('orders as o','o.id','=','od.order_id')
            ->where('od.product_id',$p->id)->where('o.status','completed')->sum('od.quantity');
          $rounded = (int)floor($avg + 0.5);
        @endphp
        @if($cnt>0 || $sold>0)
          <div class="d-flex align-items-center gap-2 mt-1">
            @if($cnt>0)
              <span class="small">
                @for($i=1;$i<=5;$i++)
                  <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                @endfor
                <span class="text-muted">{{ $avg }} ({{ $cnt }})</span>
              </span>
            @endif
            @if($sold>0)
              <span class="small text-success"><i class="bi bi-bag-check me-1"></i>Đã bán {{ number_format($sold) }}</span>
            @endif
          </div>
        @endif
        <a href="{{ route('product.show', $p->id) }}" class="stretched-link" aria-label="Xem {{ $p->name }}"></a>
      </div>
    </div>
  </div>
  @empty
    <div class="col-12 text-center text-muted">Hiện chưa có sản phẩm giảm giá.</div>
  @endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>

@include('layouts.footer')
<script>
  // Hiện modal coupon NGAY khi vào trang Sale, chỉ khi đã đăng nhập và chưa áp dụng, và chỉ 1 lần cho mỗi lần đăng nhập (per tab)
  document.addEventListener('DOMContentLoaded', function(){
    var uid = '{{ $uid }}';
    var role = '{{ $role }}';
    if (uid === 'guest' || role !== 'user') return; // chỉ hiện khi đăng nhập và là khách hàng, KHÔNG hiện cho admin
    var applied = '{{ strtoupper($applied ?? '') }}';
    var saved = '{{ strtoupper(session('saved_coupon')['code'] ?? '') }}';
    if (applied === 'DATCUTELOVE' || saved === 'DATCUTELOVE') return; // đã áp dụng hoặc đã lưu
    var shownKey = 'saleCouponShown_'+uid; // sessionStorage → 1 lần/đăng nhập (mỗi tab)
    var appliedKey = 'saleCouponApplied_'+uid; // đánh dấu đã áp dụng → không hiện nữa
    try {
      if (sessionStorage.getItem(appliedKey) === '1') return; // đã áp mã trong phiên
      if (sessionStorage.getItem(shownKey) === '1') return; // đã hiện trong phiên
      var m = document.getElementById('couponModal');
      if (m) bootstrap.Modal.getOrCreateInstance(m).show();
      sessionStorage.setItem(shownKey, '1');

      // Đánh dấu đã áp dụng khi gửi form
      var f = document.getElementById('couponForm');
      if (f) f.addEventListener('submit', function(){ sessionStorage.setItem(appliedKey, '1'); });
    } catch (e) {}
  });
</script>
@endsection
