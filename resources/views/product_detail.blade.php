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
      @php $img = 'assets/img/products/'.$product->image; @endphp
      <img id="productMainImage" src="{{ file_exists(public_path($img)) ? asset($img) : asset('assets/img/logo.png') }}" class="img-fluid w-100" alt="{{ $product->name }}">
    </div>
  </div>
  <div class="col-md-6">
    <h2 class="fw-bold">{{ $product->name }}</h2>
    <h4 class="text-primary fw-semibold mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</h4>
    <p class="text-secondary">{{ $product->description }}</p>
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
  </div>
</div>

<hr class="my-4">
<h5 class="fw-semibold mb-3">Đánh giá</h5>
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

  <form method="POST" action="{{ route('product.review.add', $product->id) }}" class="border rounded-3 p-3">
    @csrf
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <label class="col-form-label">Điểm:</label>
      </div>
      <div class="col-auto">
        <select class="form-select" name="rating">
          @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }}</option>@endfor
        </select>
      </div>
      <div class="col">
        <input type="text" name="content" class="form-control" placeholder="Viết đánh giá ngắn..." required>
      </div>
      <div class="col-auto">
        <button class="btn btn-ocean">Gửi</button>
      </div>
    </div>
  </form>

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
</script>
