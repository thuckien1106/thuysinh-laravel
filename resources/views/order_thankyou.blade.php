@extends('layouts.header')
@section('title', 'Đặt hàng thành công')

@section('content')

<style>
/* ======================= PREMIUM SUCCESS PAGE ======================= */
/* Container giới hạn chiều rộng */
.checkout-container {
  max-width: 900px;
  margin: 40px auto;
  padding: 0 15px;
}

/* Fade-in Animation */
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
  font-size: 60px;
  color: #00a86b;
  margin-bottom: 15px;
  animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes popIn {
  from { transform: scale(0); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

/* Section Box */
.section-box {
  background: #fff;
  border-radius: 18px;
  padding: 25px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.04);
  margin-bottom: 25px;
  border: 1px solid #f0f0f0;
}

/* Review stars interact */
.star-rate i {
  cursor: pointer;
  font-size: 18px;
  color: #ddd; /* Màu mặc định chưa chọn */
  transition: color 0.2s;
}
.star-rate i.active {
  color: #ffc107; /* Màu vàng khi chọn */
}

/* Buttons */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5);
  color: white !important;
  padding: 10px 22px;
  border-radius: 14px;
  font-weight: 600;
  transition: .3s ease;
  border: none;
}
.btn-ocean:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,150,136,0.3);
}

.btn-success-soft {
  background-color: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #c8e6c9;
  border-radius: 14px;
  font-weight: 600;
  padding: 10px 22px;
}
.btn-success-soft:hover {
  background-color: #c8e6c9;
}

.qr-box {
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  padding: 16px;
  background: #fff;
}
</style>

<div class="checkout-container">

  <div class="success-box fade-appear mb-4">
    <div class="success-icon">
      <i class="bi bi-check-circle-fill"></i>
    </div>

    <h2 class="fw-bold text-success mb-2">Đặt hàng thành công!</h2>
    <p class="text-dark fs-5 mb-1">Cảm ơn bạn đã tin tưởng AquaShop.</p>

    <p class="mb-0 text-muted">
      Mã đơn: <strong class="text-dark">#{{ $order->id }}</strong> • 
      Ngày: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
    </p>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="section-box fade-appear h-100">
        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Thông tin giao hàng</h5>
        <ul class="list-unstyled fs-6 mb-1">
          <li class="mb-2"><strong>Người nhận:</strong> {{ $displayName }}</li>
          <li class="mb-2"><strong>SĐT:</strong> {{ $displayPhone }}</li>
          <li class="mb-2"><strong>Địa chỉ:</strong> {{ $displayAddress }}</li>
          <li><strong>Trạng thái đơn:</strong> 
             <span class="badge bg-info text-dark">{{ $order->status }}</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-md-6">
      <div class="section-box fade-appear h-100">
        <h5 class="fw-bold mb-3"><i class="bi bi-truck text-primary me-2"></i>Thanh toán & Vận chuyển</h5>

        @php
          $payMethodMap = ['cod'=>'Thanh toán khi nhận hàng (COD)', 'online'=>'Thanh toán Online'];
          $payStatusMap = ['pending'=>'Chờ xử lý', 'paid'=>'Đã thanh toán', 'failed'=>'Thất bại'];
          $shipStatusMap = ['pending'=>'Chờ giao', 'shipping'=>'Đang giao', 'delivered'=>'Đã nhận hàng', 'cancelled'=>'Đã hủy'];
        @endphp

        <ul class="list-unstyled fs-6">
          <li class="mb-2">
            <strong>Thanh toán:</strong> <br>
            {{ $payMethodMap[$payment->method ?? 'cod'] ?? $payment->method }}
            <br>
            <span class="badge {{ ($payment->status ?? '') == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
              {{ $payStatusMap[$payment->status ?? 'pending'] ?? 'Chờ xử lý' }}
            </span>
            @if(in_array($payment->method ?? '', ['online','momo']) && ($payment->status ?? '') !== 'paid')
              <a href="{{ route('payment.momo.mock', $order->id) }}" class="ms-2 text-success small text-decoration-none">
                <i class="bi bi-check2-circle"></i> Đánh dấu đã thanh toán (demo)
              </a>
            @endif
          </li>

          <li>
            <strong>Vận chuyển:</strong> <br>
            <span class="text-primary fw-semibold">
              {{ $shipStatusMap[$shipment->status ?? 'pending'] ?? 'Đang xử lý' }}
            </span>
            @if($shipment->tracking_code ?? null)
              <div class="small text-muted mt-1">(Mã vận đơn: {{ $shipment->tracking_code }})</div>
            @endif
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- ================= ONLINE PAYMENT QR (Demo) ================= --}}
  @php
    $payMethod = strtolower($payment->method ?? '');
    $needPayQr = in_array($payMethod, ['online', 'momo']) && (($payment->status ?? 'pending') !== 'paid');
    // Thời hạn thanh toán: 15 phút từ lúc tạo đơn
    $deadline = optional($order->created_at)->addMinutes(15);
  @endphp

  @if($needPayQr)
  <div class="section-box fade-appear mt-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-qr-code-scan text-dark me-2"></i>Thanh toán QR Code</h5>
    <p class="mb-3 text-muted small">
      Vui lòng quét mã trong vòng <strong>15 phút</strong>. Sau thời gian này giao dịch sẽ tự động bị hủy.
    </p>
    @php
      // Ưu tiên QR MoMo trả về từ API; nếu không có dùng deeplink MoMo với số 0332643954
      $momoAmount = (int) ($order->total ?? 0);
      $qrContent = $momoQrUrl ?: ($momoPayUrl ?? "momo://app?action=payWithApp&phone=0332643954&amount={$momoAmount}&comment=Thanh toan don {$order->id}");
      $qrEncoded = urlencode($qrContent);
      $notePhone = $notePhone ?? ($order->phone ?? '');
    @endphp
    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
      <div class="qr-box text-center" style="min-width: 200px;">
        <div class="fw-bold mb-2 text-danger">MoMo</div>
        @if(!empty($momoQrUrl))
          <img src="{{ $momoQrUrl }}" alt="MoMo QR" class="img-fluid rounded mb-2">
        @else
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $qrEncoded }}" alt="MoMo QR" class="img-fluid rounded mb-2">
        @endif
        <div class="small text-muted">Số điện thoại: <strong>0332643954</strong></div>
        <div class="small text-muted">Nội dung: <strong>{{ $notePhone ?: '...' }}</strong></div>
        <div class="small text-muted">Số tiền: <strong>{{ number_format($momoAmount) }} đ</strong></div>
      </div>
    </div>
    <div class="mt-3 fw-bold text-danger text-center fs-5" id="qrCountdown" data-deadline="{{ $deadline }}">
      Thời gian còn lại: 15:00
    </div>
  </div>
  @endif

  <div class="section-box fade-appear mt-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-bag-check text-success me-2"></i>Sản phẩm trong đơn</h5>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
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
                <img src="{{ asset('assets/img/products/'.($it->product_image ?? 'default.png')) }}" 
                     width="50" height="50" class="rounded me-3 shadow-sm object-fit-cover">
                <div>
                    <div class="fw-semibold">{{ $it->product_name }}</div>
                    <small class="text-muted">Size/Màu: {{ $it->variant ?? 'Tiêu chuẩn' }}</small>
                </div>
              </div>
            </td>

            <td class="text-center">{{ $it->quantity }}</td>
            <td class="text-end">{{ number_format($it->price) }} đ</td>
            <td class="text-end fw-bold text-primary">
              {{ number_format($it->price * $it->quantity) }} đ
            </td>

            <td class="text-center" style="min-width: 200px;">
              @php
                // Kiểm tra trạng thái đơn hàng (Ví dụ: phải 'completed' mới được đánh giá)
                $isCompleted = strtolower($order->status) === 'completed'; 
                
                // Kiểm tra đã đánh giá chưa (Logic giả định, bạn cần thay bằng logic thật của Controller)
                // Lưu ý: Không nên query DB trong view. Tốt nhất controller nên truyền biến $it->has_reviewed
                $hasReviewed = false; 
                /* $hasReviewed = \App\Models\Review::where('product_id', $it->product_id)
                                ->where('order_id', $order->id)->exists(); 
                */
              @endphp

              @if($isCompleted && !$hasReviewed)
                <form method="POST" action="{{ route('product.review.add', $it->product_id) }}" class="review-form">
                  @csrf
                  <input type="hidden" name="rating" class="rating-input" value="5">
                  
                  <div class="star-rate mb-1">
                    @for($i=1; $i<=5; $i++)
                      <i class="bi bi-star-fill active" data-value="{{ $i }}"></i>
                    @endfor
                  </div>

                  <div class="input-group input-group-sm">
                      <input type="text" name="content" class="form-control" placeholder="Nhập đánh giá..." required>
                      <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
                  </div>
                </form>

              @elseif($hasReviewed)
                <span class="badge bg-success-subtle text-success border px-3 py-2">Đã đánh giá</span>
              @else
                <span class="text-muted small fst-italic">Chờ hoàn thành đơn</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
            <tr class="border-top">
                <td colspan="3" class="text-end text-muted">Tạm tính:</td>
                <td class="text-end">{{ number_format($order->total) }} đ</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end fw-bold fs-5">Tổng cộng:</td>
                <td class="text-end fw-bold fs-4 text-danger">{{ number_format($order->total) }} đ</td>
                <td></td>
            </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="d-flex justify-content-center gap-3 mt-5 mb-5 fade-appear">
    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2">
      <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
    </a>
    
    @if(strtolower($order->status) === 'shipping')
      <form method="POST" action="{{ route('orders.received', $order->id) }}" onsubmit="return confirm('Xác nhận bạn đã nhận được hàng?')">
        @csrf
        <button class="btn btn-success px-4 py-2 shadow-sm">
          <i class="bi bi-check2-circle me-1"></i> Đã nhận được hàng
        </button>
      </form>
    @endif
  </div>

</div> <script>
document.addEventListener('DOMContentLoaded', () => {
  // 1. Fade-in Effect
  const els = document.querySelectorAll('.fade-appear');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    });
  }, { threshold: 0.1 });
  els.forEach(el => obs.observe(el));

  // 2. QR Countdown Timer
  const timerEl = document.getElementById('qrCountdown');
  if (timerEl && timerEl.dataset.deadline) {
    const deadline = new Date(timerEl.dataset.deadline).getTime();
    
    const tick = () => {
      const now = new Date().getTime();
      const diff = deadline - now;

      if (diff <= 0) {
        timerEl.textContent = 'Đã hết thời hạn thanh toán. Đơn hàng có thể bị hủy.';
        timerEl.className = 'mt-3 text-secondary text-center fw-bold';
        // Có thể thêm logic ẩn ảnh QR tại đây
        return;
      }
      
      const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const s = Math.floor((diff % (1000 * 60)) / 1000);
      
      timerEl.textContent = `Thời gian còn lại: ${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
      requestAnimationFrame(tick);
    };
    tick();
  }

  // 3. Star Rating Logic
  // Xử lý riêng cho từng form đánh giá (vì có thể có nhiều sản phẩm)
  document.querySelectorAll('.review-form').forEach(form => {
      const stars = form.querySelectorAll('.star-rate i');
      const input = form.querySelector('.rating-input');

      stars.forEach(star => {
          // Hover effect
          star.addEventListener('mouseover', function(){
              const val = this.dataset.value;
              stars.forEach(s => {
                  s.classList.toggle('active', s.dataset.value <= val);
                  s.classList.toggle('bi-star-fill', s.dataset.value <= val);
                  s.classList.toggle('bi-star', s.dataset.value > val);
              });
          });

          // Click effect
          star.addEventListener('click', function(){
              input.value = this.dataset.value;
              // Flash effect confirm
              this.parentElement.style.transform = "scale(1.2)";
              setTimeout(() => this.parentElement.style.transform = "scale(1)", 200);
          });
      });

      // Reset hover khi chuột rời đi (trả về giá trị đã chọn trong input)
      form.querySelector('.star-rate').addEventListener('mouseleave', function(){
          const currentVal = input.value;
          stars.forEach(s => {
              const isActive = s.dataset.value <= currentVal;
              s.classList.toggle('active', isActive);
              s.className = isActive ? 'bi bi-star-fill active' : 'bi bi-star';
          });
      });
  });
});
</script>

@endsection
