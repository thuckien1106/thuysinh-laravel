@extends('layouts.header')
@section('title', $product->name)

@section('breadcrumb')
<div class="bg-light py-2">
    <div class="container">
        @include('partials.breadcrumb', [
          'items' => [
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Sản phẩm', 'url' => route('products.index')],
            ['label' => $product->name]
          ]
        ])
    </div>
</div>
@endsection

@section('content')

<style>
/* ===================== MODERN UI STYLES ===================== */
:root {
    --primary-color: #009688;
    --primary-dark: #00796b;
    --accent-color: #ff3b30; /* Màu giá/sale */
    --text-main: #2d3436;
    --text-muted: #636e72;
    --bg-surface: #ffffff;
    --radius-lg: 20px;
    --radius-md: 12px;
    --shadow-soft: 0 10px 40px rgba(0,0,0,0.05);
}

body { background-color: #f9f9f9; color: var(--text-main); }

/* Animation */
.fade-up {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-up.visible { opacity: 1; transform: translateY(0); }

/* Product Image Area */
.product-image-wrapper {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: #fff;
    box-shadow: var(--shadow-soft);
    aspect-ratio: 4 / 3;
    min-height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-main-img {
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-image-wrapper:hover .product-main-img { transform: scale(1.05); }

/* Badges */
.badge-overlay {
    position: absolute;
    top: 15px;
    z-index: 2;
    padding: 6px 14px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.85rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    backdrop-filter: blur(4px);
}
.badge-sale { background: rgba(255, 59, 48, 0.95); color: white; right: 15px; }
.badge-top { background: rgba(255, 215, 0, 0.95); color: #333; left: 15px; }

/* Product Info */
.price-block {
    display: inline-flex;
    align-items: baseline;
    gap: 12px;
    background: #fff;
    padding: 10px 0;
}
.price-final { font-size: 2rem; font-weight: 800; color: var(--accent-color); }
.price-original { text-decoration: line-through; color: var(--text-muted); font-size: 1.1rem; }

/* Quantity & Buttons */
.qty-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 50px;
    background: #fff;
    padding: 2px;
    width: fit-content;
}
.qty-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--text-main);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.qty-btn:hover { background: #f1f2f6; }
.qty-input {
    border: none;
    width: 50px;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-main);
    background: transparent;
}
.qty-input:focus { outline: none; }

.btn-add-cart {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    box-shadow: 0 4px 15px rgba(0, 150, 136, 0.3);
    transition: all 0.3s;
}
.btn-add-cart:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 150, 136, 0.4);
}
.btn-disabled {
    background: #b2bec3;
    color: #fff;
    cursor: not-allowed;
    border-radius: 50px;
    padding: 12px 30px;
    border: none;
}

/* Info Cards */
.detail-group {
    background: #fff;
    border-radius: var(--radius-md);
    padding: 20px;
    margin-bottom: 15px;
    border: 1px solid #f1f2f6;
}
.detail-label { font-weight: 700; color: var(--text-main); margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }

/* Reviews */
.review-item {
    background: #fff;
    border-radius: var(--radius-md);
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #eee;
}
</style>

<div class="container py-4 fade-up">
    <div class="row g-5">

        <div class="col-lg-6">
            <div class="product-image-wrapper mb-3">
                @php
                    $img = 'assets/img/products/'.$product->image;
                    $ver = file_exists(public_path($img)) ? '?v='.filemtime(public_path($img)) : '';
                @endphp

                <img id="productMainImage"
                     src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}"
                     class="img-fluid product-main-img"
                     alt="{{ $product->name }}">

                @if(($isTop ?? false))
                    <span class="badge-overlay badge-top">🔥 Top Bán Chạy</span>
                @endif

                @php $percent = optional($product->activeDiscount)->percent; @endphp
                @if($percent)
                    <span class="badge-overlay badge-sale">-{{ $percent }}%</span>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="fw-bold mb-2 text-dark" style="letter-spacing: -0.5px;">{{ $product->name }}</h1>

            @if(($reviewCount ?? 0) > 0 || ($soldCount ?? 0) > 0)
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($reviewCount > 0)
                        <div class="text-warning d-flex align-items-center">
                            <span class="fw-bold text-dark me-2">{{ number_format($avgRating,1) }}</span>
                            @php $rounded = floor($avgRating + 0.5); @endphp
                            @for($i=1;$i<=5;$i++)
                                <i class="bi {{ $i <= $rounded ? 'bi-star-fill' : 'bi-star' }} small"></i>
                            @endfor
                            <span class="text-muted small ms-1">({{ $reviewCount }} đánh giá)</span>
                        </div>
                    @endif

                    @if($soldCount > 0)
                        <div class="text-success small fw-semibold bg-success bg-opacity-10 px-2 py-1 rounded">
                            <i class="bi bi-bag-check-fill me-1"></i>Đã bán {{ number_format($soldCount) }}
                        </div>
                    @endif
                </div>
            @endif

            <div class="price-block mb-3">
                @if($percent)
                    <span class="price-final">{{ number_format($product->final_price) }} đ</span>
                    <span class="price-original">{{ number_format($product->price) }} đ</span>
                @else
                    <span class="price-final" style="color: var(--primary-color);">{{ number_format($product->price) }} đ</span>
                @endif
            </div>

            <p class="text-secondary mb-4" style="line-height: 1.6;">
                {{ str_replace('\\n', "\n", $product->short_description ?: $product->description) }}
            </p>

            <div class="mb-5">
                @if($product->quantity > 0)
                    <form method="POST" action="{{ route('cart.add') }}" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="qty-wrapper">
                                <button type="button" class="qty-btn" id="qtyMinus"><i class="bi bi-dash"></i></button>
                                <input type="number" id="qtyInput" name="quantity" class="qty-input" 
                                       value="1" min="1" max="{{ $product->quantity }}" data-stock="{{ $product->quantity }}">
                                <button type="button" class="qty-btn" id="qtyPlus"><i class="bi bi-plus"></i></button>
                            </div>

                            <button type="submit" class="btn-add-cart flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                        </div>
                        <div class="mt-2 text-muted small">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>Còn hàng ({{ $product->quantity }} sản phẩm)
                        </div>
                    </form>
                @else
                    <div class="p-3 bg-light rounded border border-secondary border-opacity-25 d-inline-block">
                        <button type="button" class="btn-disabled" disabled>
                            <i class="bi bi-emoji-frown me-2"></i>Sản phẩm tạm hết hàng
                        </button>
                        <div class="mt-2 text-danger small fw-semibold text-center">
                            Vui lòng quay lại sau!
                        </div>
                    </div>
                @endif
            </div>

            <div class="details-wrapper">
                <div class="detail-group">
                    <div class="detail-label text-primary"><i class="bi bi-file-text"></i> Mô tả chi tiết</div>
                    <div class="text-secondary small">
                        {!! nl2br(e(str_replace('\\n', "\n", $product->long_description ?: $product->description))) !!}
                    </div>
                </div>

                <div class="detail-group">
                    <div class="detail-label text-info"><i class="bi bi-cpu"></i> Thông số kỹ thuật</div>
                    <div class="text-secondary small font-monospace bg-light p-2 rounded">
                        {!! nl2br(e($product->specs ?: 'Đang cập nhật')) !!}
                    </div>
                </div>

                <div class="detail-group mb-0">
                    <div class="detail-label text-success"><i class="bi bi-shield-check"></i> Hướng dẫn chăm sóc</div>
                    <div class="text-secondary small fst-italic">
                        {!! nl2br(e($product->care_guide ?: 'Đang cập nhật')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <hr class="text-muted opacity-25 mb-4">
            <h4 id="review" class="fw-bold mb-4"><i class="bi bi-chat-square-quote me-2"></i>Đánh giá sản phẩm</h4>

            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="row">
                @forelse($reviews as $r)
                    <div class="col-md-6">
                        <div class="review-item h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">Khách hàng</span>
                                <small class="text-muted">{{ $r->created_at->format('d/m/Y') }}</small>
                            </div>
                            <div class="text-warning mb-2" style="font-size: 0.9rem;">
                                {{ str_repeat('★', (int)$r->rating) }}<span class="text-muted opacity-25">{{ str_repeat('★', 5-(int)$r->rating) }}</span>
                            </div>
                            <p class="mb-0 text-secondary">{{ $r->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <div class="text-muted fst-italic">Chưa có đánh giá nào cho sản phẩm này.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection

<script>
document.addEventListener("DOMContentLoaded", function(){

  // Fade-in animation
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.1 });
  document.querySelectorAll(".fade-up").forEach(el => observer.observe(el));

  // Logic: Quantity Handler
  const qty = document.getElementById('qtyInput');
  const minusBtn = document.getElementById('qtyMinus');
  const plusBtn = document.getElementById('qtyPlus');
  
  if (qty && minusBtn && plusBtn) {
    const maxStock = parseInt(qty.dataset.stock || qty.getAttribute('max')) || 1;
    
    minusBtn.onclick = () => {
      const current = parseInt(qty.value) || 1;
      if (current > 1) qty.value = current - 1;
    };
    
    plusBtn.onclick = () => {
      const current = parseInt(qty.value) || 1;
      if (current < maxStock) qty.value = current + 1;
    };
  }

  // Logic: Add to Cart Ajax
  const form = document.getElementById('addToCartForm');
  if (form) {
    form.addEventListener('submit', function(e){
      e.preventDefault();
      let fd = new FormData(form);

      // UI Feedback: Loading state for button
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
      btn.disabled = true;

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
      .catch(()=> form.submit())
      .finally(() => {
        // Reset button
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 500);
      });
    });
  }
});
</script>
