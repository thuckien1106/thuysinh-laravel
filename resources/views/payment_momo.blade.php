@extends('layouts.header')
@section('title','Thanh toán MoMo')
@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h4 class="mb-2">Thanh toán MoMo cho đơn #{{ $order->id }}</h4>
          <p class="text-muted mb-3">Số tiền: <strong>{{ number_format($order->total) }} đ</strong></p>

          @if($qrCodeUrl)
            <img src="{{ $qrCodeUrl }}" alt="QR MoMo" class="img-fluid rounded mb-3" style="max-width: 260px;">
          @endif

          <div class="d-flex flex-column gap-2 align-items-center">
            @if($payUrl)
              <a class="btn btn-primary" href="{{ $payUrl }}" target="_blank" rel="noreferrer">Mở ứng dụng MoMo</a>
            @endif
            <div id="momoCountdown" class="fw-bold text-danger"></div>
          </div>

          <p class="mt-3 text-muted small">Quét mã bằng app MoMo hoặc bấm nút để chuyển tới MoMo.</p>
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
