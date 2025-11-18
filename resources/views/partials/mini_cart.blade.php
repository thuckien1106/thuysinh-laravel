<style>
/* ================= MINI CART PREMIUM ================= */
.mini-cart-header {
  background: linear-gradient(90deg, #0091ea, #00bfa5);
  color: white;
  padding: 14px 18px;
  border-bottom: 1px solid rgba(255,255,255,0.3);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.mini-cart-title {
  font-size: 1.1rem;
  font-weight: 700;
}

.mini-cart-body {
  padding: 16px;
}

/* ITEM */
.mini-item {
  border: none;
  border-radius: 14px;
  padding: 12px 14px;
  background: #f8fbff;
  box-shadow: 0 3px 8px rgba(0,0,0,0.06);
  margin-bottom: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.mini-item:hover {
  background: #eef7ff;
}

/* BUTTONS */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  color: white !important;
  font-weight: 600;
  border-radius: 12px;
  transition: .25s ease;
}
.btn-ocean:hover {
  transform: translateY(-2px);
}

.btn-outline-ocean {
  border: 2px solid #009688;
  color: #009688 !important;
  border-radius: 12px;
  font-weight: 600;
  transition: .25s;
}
.btn-outline-ocean:hover {
  background: #009688;
  color: white !important;
}
</style>

<!-- ================= MINI CART ================= -->
<div class="mini-cart-header">
  <div class="mini-cart-title">
    <i class="bi bi-cart-check-fill me-2"></i> Giỏ hàng ({{ $count }})
  </div>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
</div>

<div class="mini-cart-body">

  @if(empty($cart))
    <div class="text-center text-muted py-4">
      <i class="bi bi-bag-dash fs-1"></i>
      <p class="mt-2">Chưa có sản phẩm nào.</p>
    </div>

  @else
    @foreach($cart as $it)
      <div class="mini-item">
        <div>
          <div class="fw-semibold">{{ $it['name'] }}</div>
          <div class="small text-muted">x{{ $it['quantity'] }}</div>
        </div>
        <div class="fw-bold text-primary">
          {{ number_format($it['price'] * $it['quantity']) }} đ
        </div>
      </div>
    @endforeach

    <div class="d-flex justify-content-between mt-3 mb-3">
      <span class="fw-semibold">Tạm tính</span>
      <span class="fw-bold text-primary">{{ number_format($total) }} đ</span>
    </div>

    <div class="d-grid gap-2">
      <a href="{{ route('cart') }}" class="btn btn-outline-ocean">
        <i class="bi bi-basket3 me-1"></i> Xem giỏ hàng
      </a>
      <a href="{{ route('checkout') }}" class="btn btn-ocean">
        <i class="bi bi-credit-card me-1"></i> Thanh toán
      </a>
    </div>

  @endif

</div>
