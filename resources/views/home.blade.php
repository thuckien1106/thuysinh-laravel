@extends('layouts.header')
@section('title', 'Trang chủ')
@section('content')

<section class="hero-ocean mb-5">
  <div class="text">
  <h1 style="
  font-family:'Poppins',sans-serif;
  font-size:48px;
  font-weight:700;
  text-align:center;
  background:linear-gradient(90deg,#1b5e20,#00796b,#00bfa5,#1b5e20);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-size:300% 300%;
  animation:moveGradient 6s ease infinite, fadeUp 1.5s ease forwards;
  text-shadow:0 0 25px rgba(0,255,204,0.3);
  letter-spacing:1px;
  margin:60px 20px;
  transition:transform 0.4s ease, text-shadow 0.4s ease;
">
  Thế giới thủy sinh ngay trong ngôi nhà bạn
</h1>

<style>
@keyframes moveGradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

    <p>Cây, cá cảnh và phụ kiện chất lượng, giao nhanh, hướng dẫn tận tâm.
      Mang lại không gian xanh thư giãn giữa cuộc sống hiện đại.</p>
    <a href="#products" class="btn btn-ocean">Khám phá ngay</a>
  </div>
  <img src="{{ asset('assets/img/hero_fish.jpg') }}" alt="AquaShop Hero">
</section>
<script>
  // Smooth scroll with offset for sticky navbar
  document.addEventListener('DOMContentLoaded', function(){
    var link = document.querySelector('a[href=\"#products\"]');
    var target = document.getElementById('products');
    if(link && target){
      link.addEventListener('click', function(e){
        e.preventDefault();
        var top = target.getBoundingClientRect().top + window.scrollY - 80; // offset for navbar
        window.scrollTo({ top: top, behavior: 'smooth' });
      });
    }
  });
</script>

@php $uid = session('admin')->id ?? 'guest'; $applied = session('coupon')['code'] ?? null; $role = session('admin')->role ?? 'guest'; @endphp
<!-- Modal coupon hiện khi vào Trang chủ (đăng nhập, chưa áp dụng, 1 lần/đăng nhập) -->
<div class="modal fade coupon-modal" id="couponModalHome" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(90deg,#0b5ed7,#3aa0ff); color:#fff">
        <h5 class="modal-title"><i class="bi bi-fire me-2"></i>Ưu đãi dành riêng cho bạn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3" style="background:#fff4e6;border:1px solid #ffe8cc;border-radius:12px;padding:.75rem">
          Nhập mã <span style="display:inline-block;padding:.25rem .5rem;border-radius:8px;background:#fff;color:#d9480f;border:1px dashed #ffd8a8">datcutelove</span> để giảm <strong>15%</strong> đơn hàng.
        </div>
        <form method="POST" action="{{ route('coupon.save') }}" id="couponFormHome">
          @csrf
          <div class="input-group">
            <input type="text" name="code" class="form-control form-control-lg" placeholder="Nhập mã: datcutelove" value="datcutelove">
            <button class="btn btn-ocean">Lưu mã</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<h2 id="products" class="section-title mb-3">SẢN PHẨM NỔI BẬT</h2>

<div class="row g-4 mb-4">
  @foreach(($featuredProducts ?? collect()) as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card product-card h-100">
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
      <div class="card-body text-center position-relative">
        <h6 class="card-title mb-1">{{ $p->name }}</h6>
        @if($percent)
          <div class="d-flex justify-content-center align-items-baseline gap-2 mb-2">
            <div class="text-danger fw-bold">{{ number_format($p->final_price, 0, ',', '.') }} đ</div>
            <div class="text-muted small text-decoration-line-through">{{ number_format($p->price, 0, ',', '.') }} đ</div>
            <span class="badge bg-danger-subtle text-danger border">-{{ $percent }}%</span>
          </div>
        @else
          <div class="text-primary fw-semibold mb-2">{{ number_format($p->price, 0, ',', '.') }} đ</div>
        @endif
        @php
          $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
          $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
          $sold = (int)\Illuminate\Support\Facades\DB::table('order_details as od')->join('orders as o','o.id','=','od.order_id')->where('od.product_id',$p->id)->where('o.status','completed')->sum('od.quantity');
          $rounded = (int)floor($avg + 0.5);
        @endphp
        @if($cnt>0 || $sold>0)
          <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
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
        <a href="{{ route('product.show', $p->id) }}" class="btn btn-outline-ocean btn-sm">Xem chi tiết</a>
        <a href="{{ route('product.show', $p->id) }}" class="stretched-link" aria-label="Xem {{ $p->name }}"></a>
      </div>
    </div>
  </div>
  @endforeach
</div>

<h2 class="section-title mb-3">SẢN PHẨM KHÁC</h2>

<div class="row g-4">
  @foreach(($otherProducts ?? collect()) as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card product-card h-100">
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
      <div class="card-body text-center position-relative">
        <h6 class="card-title mb-1">{{ $p->name }}</h6>
        @if($percent)
          <div class="d-flex justify-content-center align-items-baseline gap-2 mb-2">
            <div class="text-danger fw-bold">{{ number_format($p->final_price, 0, ',', '.') }} đ</div>
            <div class="text-muted small text-decoration-line-through">{{ number_format($p->price, 0, ',', '.') }} đ</div>
            <span class="badge bg-danger-subtle text-danger border">-{{ $percent }}%</span>
          </div>
        @else
          <div class="text-primary fw-semibold mb-2">{{ number_format($p->price, 0, ',', '.') }} đ</div>
        @endif
        @php
          $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
          $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
          $sold = (int)\Illuminate\Support\Facades\DB::table('order_details as od')->join('orders as o','o.id','=','od.order_id')->where('od.product_id',$p->id)->where('o.status','completed')->sum('od.quantity');
          $rounded = (int)floor($avg + 0.5);
        @endphp
        @if($cnt>0 || $sold>0)
          <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
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
        <a href="{{ route('product.show', $p->id) }}" class="btn btn-outline-ocean btn-sm">Xem chi tiết</a>
        <a href="{{ route('product.show', $p->id) }}" class="stretched-link" aria-label="Xem {{ $p->name }}"></a>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="text-center mt-4">
  <a href="{{ route('products.index') }}" class="btn btn-ocean">Xem thêm tất cả sản phẩm</a>
  <div class="small text-muted mt-1">Hoặc dùng thanh tìm kiếm để lọc nhanh theo nhu cầu</div>
</div>

@include('layouts.footer')
<script>
  // Hiện modal coupon ở Trang chủ: khi đăng nhập, chưa áp dụng hoặc chưa lưu, chỉ 1 lần/đăng nhập (per tab)
  document.addEventListener('DOMContentLoaded', function(){
    var uid = '{{ $uid }}';
    var role = '{{ $role }}';
    if (uid === 'guest' || role !== 'user') return; // không hiện cho admin
    var applied = '{{ strtoupper($applied ?? '') }}';
    var saved = '{{ strtoupper(session('saved_coupon')['code'] ?? '') }}';
    if (applied === 'DATCUTELOVE' || saved === 'DATCUTELOVE') return;
    var shownKey = 'homeCouponShown_'+uid;
    var appliedKey = 'homeCouponApplied_'+uid;
    try {
      if (sessionStorage.getItem(appliedKey) === '1') return;
      if (sessionStorage.getItem(shownKey) === '1') return;
      var m = document.getElementById('couponModalHome');
      if (m) bootstrap.Modal.getOrCreateInstance(m).show();
      sessionStorage.setItem(shownKey, '1');
      var f = document.getElementById('couponFormHome');
      if (f) f.addEventListener('submit', function(){ sessionStorage.setItem(appliedKey, '1'); });
    } catch(e) {}
  });
</script>
@endsection




