@extends('layouts.header')
@section('title', $product->name)
@section('breadcrumb')
  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Sản phẩm', 'url' => route('products.index')],
    ['label' => $product->name]
  ]])
@endsection
@section('content')

<div class="row g-4">
  <div class="col-md-6">
    <div class="shadow-sm rounded-4 overflow-hidden">
      @php $img = 'assets/img/products/'.$product->image; $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : ''; @endphp
      <div class="position-relative">
        <img id="productMainImage" src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}" class="img-fluid w-100" alt="{{ $product->name }}">
        @if(($isTop ?? false))
          <span class="badge bg-warning text-dark position-absolute" style="top:8px; left:8px;">Top</span>
        @endif
        @php $percent = optional($product->activeDiscount)->percent; @endphp
        @if($percent)
          <span class="badge badge-sale position-absolute" style="top:8px; right:8px;">-{{ $percent }}%</span>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <h2 class="fw-bold">{{ $product->name }}</h2>
    @php $percent = optional($product->activeDiscount)->percent; @endphp
    @if($percent)
      <div class="d-flex align-items-baseline gap-2 mb-3">
        <h4 class="text-danger fw-bold mb-0">{{ number_format($product->final_price, 0, ',', '.') }} đ</h4>
        <div class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} đ</div>
        <span class="badge bg-danger-subtle text-danger border">-{{ $percent }}%</span>
      </div>
    @else
      <h4 class="text-primary fw-semibold mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</h4>
    @endif

    @if(($reviewCount ?? 0) > 0 || ($soldCount ?? 0) > 0)
      <div class="d-flex align-items-center gap-3 mb-2">
        @if(($reviewCount ?? 0) > 0)
          <div class="d-flex align-items-center gap-1" title="{{ number_format($avgRating,1) }} / 5 từ {{ $reviewCount }} đánh giá">
            @php $rounded = floor($avgRating + 0.5); @endphp
            @for($i=1;$i<=5;$i++)
              <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
            @endfor
            <span class="small text-muted">{{ number_format($avgRating,1) }} ({{ $reviewCount }})</span>
          </div>
        @endif
        @if(($soldCount ?? 0) > 0)
          <div class="small text-success"><i class="bi bi-bag-check me-1"></i>Đã bán {{ number_format($soldCount) }}</div>
        @endif
      </div>
    @endif
    @if($product->short_description)
      <p class="text-secondary">{{ str_replace('\\n', "\n", $product->short_description) }}</p>
    @else
      <p class="text-secondary">{{ str_replace('\\n', "\n", $product->description) }}</p>
    @endif
    <div class="mt-4 d-flex align-items-center gap-3">
      <form method="POST" action="{{ route('cart.add') }}" class="d-flex align-items-center gap-2" id="addToCartForm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="input-group" style="width:140px">
          <button class="btn btn-outline-secondary" type="button" id="qtyMinus">-</button>
          <input type="number" name="quantity" id="qtyInput" value="1" min="1" class="form-control text-center">
          <button class="btn btn-outline-secondary" type="button" id="qtyPlus">+</button>
        </div>
        <button type="submit" class="btn btn-ocean me-2" id="addToCartBtn">Thêm vào giỏ hàng</button>
      </form>
      
    </div>
    <div class="mt-4">
      <h5 class="fw-semibold mb-2">Chi tiết sản phẩm</h5>
      <div class="bg-light rounded-3 p-3 mb-3">
        {!! nl2br(e(str_replace('\\n', "\n", $product->long_description ?: $product->description))) !!}
      </div>
      <div class="bg-light rounded-3 p-3 mb-3">
        <h6 class="fw-semibold mb-2">Thông số kỹ thuật</h6>
        <div class="small">{!! nl2br(e(str_replace('\\n', "\n", $product->specs ?: 'Đang cập nhật'))) !!}</div>
      </div>
      <div class="bg-light rounded-3 p-3">
        <h6 class="fw-semibold mb-2">Hướng dẫn chăm sóc</h6>
        <div class="small">{!! nl2br(e(str_replace('\\n', "\n", $product->care_guide ?: 'Đang cập nhật'))) !!}</div>
      </div>
    </div>
  </div>
</div>

<hr class="my-4">
<h5 id="review" class="fw-semibold mb-3">Đánh giá</h5>
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="mb-3">
  @forelse(($reviews ?? []) as $r)
    <div class="border rounded-3 p-3 mb-2">
      <div class="small text-muted">{{ $r->created_at }}</div>
      <div class="fw-semibold">{{ str_repeat('★', (int)$r->rating) }}{{ str_repeat('☆', 5-(int)$r->rating) }}</div>
      <div>{{ $r->content }}</div>
    </div>
  @empty
    <div class="text-muted">Chưa có đánh giá.</div>
  @endforelse
  </div>

  <!-- Bỏ form đánh giá tại trang sản phẩm vì chỉ đánh giá sau khi nhận hàng -->

@include('layouts.footer')
@endsection

<script>
document.addEventListener('DOMContentLoaded', function(){
  const qty = document.getElementById('qtyInput');
  const minus = document.getElementById('qtyMinus');
  const plus = document.getElementById('qtyPlus');
  minus && minus.addEventListener('click', ()=>{ qty.stepDown(); });
  plus && plus.addEventListener('click', ()=>{ qty.stepUp(); });

  // Intercept add-to-cart to open mini cart
  const form = document.getElementById('addToCartForm');
  if (form) {
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const fd = new FormData(form);
      fetch(form.action, { method:'POST', body: fd, headers: { 'X-Requested-With':'XMLHttpRequest' } })
        .then(()=> fetch('{{ route('cart.mini') }}'))
        .then(r => r.json())
        .then(data => {
          const wrap = document.getElementById('miniCartBody');
          if (wrap && data && data.html){ wrap.innerHTML = data.html; }
          if (typeof updateCartBadge === 'function') updateCartBadge(data.count);
          const el = document.getElementById('cartOffcanvas');
          if (el) bootstrap.Offcanvas.getOrCreateInstance(el).show();
        })
        .catch(()=>{
          // On error, fallback to normal submit
          form.submit();
        });
    });
  }
});

// Không cần xử lý rating tại trang sản phẩm
</script>
