@extends('layouts.header')
@section('title','Thanh toán MoMo')
@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 border-end">
              <div class="text-muted small mb-2">Thanh toán cho đơn #{{ $order->id }}</div>
              <h4 class="fw-bold mb-3">Quét mã thanh toán</h4>
              <p class="text-muted">Vui lòng mở ứng dụng MoMo và quét mã để hoàn tất thanh toán.</p>

              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i>Số điện thoại: <strong>0332643954</strong></li>
                <li class="mb-2"><i class="bi bi-chat-dots me-2 text-primary"></i>Nội dung: <strong>{{ $transferContent }}</strong></li>
                <li class="mb-2"><i class="bi bi-cash-stack me-2 text-primary"></i>Số tiền: <strong class="text-danger">{{ number_format($order->total) }} đ</strong></li>
              </ul>

              <div id="momoCountdown" class="badge bg-danger-subtle text-danger fw-semibold px-3 py-2"></div>

              @if($payUrl)
              <a class="btn btn-primary mt-3" href="{{ $payUrl }}" target="_blank" rel="noreferrer">
                <i class="bi bi-phone"></i> Mở ứng dụng MoMo
              </a>
              @endif
            </div>

            <div class="col-md-6 text-center">
              <div class="border rounded-3 p-3 bg-light">
                <div class="fw-semibold mb-2">MoMo QR</div>
                <img src="{{ $qrCodeUrl }}" alt="MoMo QR" class="img-fluid rounded shadow-sm" style="max-width: 240px;">
                <div class="text-muted small mt-2">Quét bằng app MoMo để thanh toán</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const el = document.getElementById('momoCountdown');
    if(!el) return;
    const expire = {{ $expireAtTs ?? 0 }} * 1000;
    const tick = () => {
      const now = Date.now();
      const diff = expire - now;
      if (diff <= 0) { el.textContent = 'Hết hạn thanh toán'; return; }
      const m = Math.floor(diff/60000).toString().padStart(2,'0');
      const s = Math.floor((diff%60000)/1000).toString().padStart(2,'0');
      el.textContent = `Thời gian còn lại: ${m}:${s}`;
      requestAnimationFrame(tick);
    };
    tick();
  })();
</script>
@endsection
