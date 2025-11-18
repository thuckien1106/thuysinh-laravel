@extends('layouts.header')
@section('title', 'Đặt hàng thành công')

@section('content')

<style>
/* ======================= PREMIUM SUCCESS PAGE ======================= */

/* Fade-in */
.fade-appear {
  opacity: 0;
  transform: translateY(20px);
  transition: all .6s ease;
}
.fade-appear.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Success Box */
.success-box {
  background: linear-gradient(135deg, #e3fcec, #d0f5df, #c2f3d6);
  border-radius: 28px;
  padding: 40px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  border: 1px solid rgba(0,150,136,.18);
  text-align: center;
}

.success-icon {
  font-size: 52px;
  color: #00a86b;
  margin-bottom: 10px;
}

/* Section */
.section-box {
  background: #fff;
  border-radius: 18px;
  padding: 25px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.06);
  margin-bottom: 25px;
}

/* Review stars */
.star-rate i {
  cursor: pointer;
  font-size: 18px;
}

/* Buttons */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5);
  color: white !important;
  padding: 10px 22px;
  border-radius: 14px;
  font-weight: 600;
  transition: .3s ease;
}
.btn-ocean:hover {
  transform: translateY(-2px);
  color: #fff !important;
}

.btn-success {
  border-radius: 14px;
  font-weight: 600;
}
</style>


<!-- ================= SUCCESS HEADER ================= -->
<div class="success-box fade-appear mb-4">
  <div class="success-icon">
    <i class="bi bi-check-circle-fill"></i>
  </div>

  <h2 class="fw-bold text-success mb-2">Đặt hàng thành công!</h2>
  <p class="text-dark fs-5 mb-1">Cảm ơn bạn đã tin tưởng AquaShop.</p>

  <p class="mb-0">
    Mã đơn: <strong>#{{ $order->id }}</strong> •  
    Ngày: {{ $order->created_at ?? now() }}
  </p>
</div>


<!-- ================= SHIPPING INFO ================= -->
<div class="section-box fade-appear">
  <h4 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Thông tin giao hàng</h4>

  <ul class="list-unstyled fs-6 mb-1">
    <li><strong>Tên:</strong> {{ $order->customer_name }}</li>
    <li><strong>Địa chỉ:</strong> {{ $order->customer_address }}</li>
    <li><strong>Trạng thái:</strong> {{ $order->status }}</li>
  </ul>
</div>


<!-- ================= PAYMENT & SHIPPING ================= -->
<div class="section-box fade-appear">
  <h4 class="fw-bold mb-3"><i class="bi bi-truck text-primary me-2"></i>Thanh toán & vận chuyển</h4>

  @php
    $payMethodMap = ['cod'=>'Thanh toán khi nhận hàng','online'=>'Trực tuyến'];
    $payStatusMap = ['pending'=>'Chờ xử lý','paid'=>'Đã thanh toán','failed'=>'Thất bại'];
    $shipCarrierMap = ['local'=>'Nội bộ'];
    $shipStatusMap = ['pending'=>'Chờ giao','shipping'=>'Đang giao','delivered'=>'Đã nhận hàng','cancelled'=>'Đã hủy'];
  @endphp

  <ul class="list-unstyled fs-6">
    <li><strong>Thanh toán:</strong> 
      {{ $payMethodMap[$payment->method ?? 'cod'] ?? 'Thanh toán' }} — 
      <span class="text-success fw-semibold">
        {{ $payStatusMap[$payment->status ?? 'pending'] }}
      </span>
    </li>

    <li><strong>Vận chuyển:</strong> 
      {{ $shipCarrierMap[$shipment->carrier ?? 'local'] }} — 
      <span class="text-primary fw-semibold">
        {{ $shipStatusMap[$shipment->status ?? 'pending'] }}
      </span>
      @if($shipment->tracking_code ?? null)
        (Mã: {{ $shipment->tracking_code }})
      @endif
    </li>
  </ul>
</div>


<!-- ================= PRODUCT LIST ================= -->
<div class="section-box fade-appear">
  <h4 class="fw-bold mb-3"><i class="bi bi-bag-check text-success me-2"></i>Sản phẩm trong đơn</h4>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th class="text-center">SL</th>
          <th class="text-end">Đơn giá</th>
          <th class="text-end">Thành tiền</th>
          <th class="text-center">Đánh giá</th>
        </tr>
      </thead>
      <tbody>

        @foreach($items as $it)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <img src="{{ asset('assets/img/products/'.$it->product_image) }}" width="56" class="rounded me-3 shadow-sm">
              <div>{{ $it->product_name }}</div>
            </div>
          </td>

          <td class="text-center">{{ $it->quantity }}</td>

          <td class="text-end">{{ number_format($it->price) }} đ</td>

          <td class="text-end fw-semibold text-primary">
            {{ number_format($it->price * $it->quantity) }} đ
          </td>

          <td class="text-center">
            @php
              $statusCode = \App\Models\Order::normalizeStatus($order->status);
              $hasReviewed = \App\Models\Review::where('product_id',$it->product_id)
                              ->where('user_id', session('admin')->id ?? null)
                              ->exists();
            @endphp

            @if($statusCode==='completed' && !$hasReviewed)
              <form method="POST" action="{{ route('product.review.add', $it->product_id) }}" class="d-inline-flex flex-column flex-sm-row align-items-center gap-2 review-inline-form">
                @csrf
                <input type="hidden" name="rating" value="5">

                <div class="star-rate">
                  @for($i=1;$i<=5;$i++)
                    <i class="bi bi-star" data-value="{{ $i }}"></i>
                  @endfor
                </div>

                <textarea name="content" class="form-control form-control-sm" required placeholder="Cảm nhận của bạn..." rows="2"></textarea>

                <button class="btn btn-sm btn-ocean px-3">Gửi</button>
              </form>

            @elseif($hasReviewed)
              <span class="badge bg-success-subtle text-success border px-3 py-2">Đã đánh giá</span>

            @else
              <span class="text-muted small">Chờ nhận hàng</span>
            @endif
          </td>
        </tr>
        @endforeach

      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Tổng cộng:</th>
          <th class="text-end fs-5 text-primary">{{ number_format($order->total) }} đ</th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>


<!-- ================= BOTTOM ACTION ================= -->
<div class="d-flex justify-content-between align-items-center fade-appear">
  @if($statusCode === 'shipping')
    <form method="POST" action="{{ route('orders.received',$order->id) }}" onsubmit="return confirm('Xác nhận đã nhận hàng?')">
      @csrf
      <button class="btn btn-success px-4">
        <i class="bi bi-check2-circle me-1"></i> Tôi đã nhận hàng
      </button>
    </form>
  @endif

  <a href="{{ route('home') }}" class="btn btn-ocean px-4">
    <i class="bi bi-house-door-fill me-1"></i> Về trang chủ
  </a>
</div>


<!-- Fade-in on scroll -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const els = document.querySelectorAll('.fade-appear');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    });
  }, { threshold: 0.15 });

  els.forEach(el => obs.observe(el));
});
</script>

@endsection
