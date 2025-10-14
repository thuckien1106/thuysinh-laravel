<div class="offcanvas-header">
  <h5 class="offcanvas-title">Giỏ hàng ({{ $count }})</h5>
  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
  @if(empty($cart))
    <div class="text-muted">Chưa có sản phẩm nào.</div>
  @else
    <ul class="list-group mb-3">
      @foreach($cart as $it)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold">{{ $it['name'] }}</div>
            <small class="text-muted">x{{ $it['quantity'] }}</small>
          </div>
          <div>{{ number_format($it['price'] * $it['quantity'], 0, ',', '.') }} đ</div>
        </li>
      @endforeach
    </ul>
    <div class="d-flex justify-content-between mb-3">
      <span class="fw-semibold">Tạm tính</span>
      <span class="fw-bold text-primary">{{ number_format($total, 0, ',', '.') }} đ</span>
    </div>
    <div class="d-grid gap-2">
      <a href="{{ route('cart') }}" class="btn btn-outline-primary">Xem giỏ hàng</a>
      <a href="{{ route('checkout') }}" class="btn btn-primary">Thanh toán</a>
    </div>
  @endif
</div>

