@extends('layouts.header')

@php
    $orderStatusCode = \App\Models\Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status);
    $pageTitle = $orderStatusCode === 'completed' ? 'Cảm ơn quý khách!' : 'Đặt hàng thành công';
@endphp

@section('title', $pageTitle)

@section('content')

<style>
/* ======================= FIX LỖI KHOẢNG TRẮNG HEADER (FORCE) ======================= */
/* 1. Reset Margin */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    overflow-x: hidden;
}

/* 2. Hack đẩy ngược trang web lên để che khoảng trắng */
/* Chỉ dùng cách này nếu không sửa được lỗi BOM trong file */
body {
    /* Đẩy toàn bộ body lên 20px (hoặc số px tương ứng với khoảng trắng bạn thấy) */
    /* Hãy thử chỉnh số này: -10px, -20px đến khi hết trắng */
    margin-top: -22px !important; 
    
    /* Đảm bảo nền khớp */
    background-color: var(--bg-page);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: var(--text-main);
}

/* 3. Style giao diện Harmony (Giữ nguyên) */
:root {
    --primary: #009688;
    --primary-soft: #e0f2f1;
    --text-main: #2b3445;
    --text-light: #7d879c;
    --bg-page: #f6f9fc;
    --card-bg: #ffffff;
    --radius-lg: 24px;
    --radius-md: 16px;
    --shadow-soft: 0 10px 40px rgba(0,0,0,0.03);
    --shadow-hover: 0 15px 50px rgba(0,0,0,0.08);
}

.checkout-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px; 
}

/* --- (Các class CSS khác giữ nguyên như cũ) --- */
.harmony-card { background: var(--card-bg); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft); border: 1px solid rgba(0,0,0,0.02); overflow: hidden; margin-bottom: 24px; transition: all 0.3s ease; }
.harmony-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.fade-up { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-up.visible { opacity: 1; transform: translateY(0); }
.success-banner { text-align: center; padding: 50px 20px; background: linear-gradient(180deg, #fff 0%, #fafffd 100%); }
.success-icon-box { width: 80px; height: 80px; background: var(--primary); color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px; box-shadow: 0 10px 20px rgba(0, 150, 136, 0.2); animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
@keyframes popIn { 0% { transform: scale(0); } 100% { transform: scale(1); } }
.order-pill { display: inline-block; background: #f0f2f5; padding: 8px 20px; border-radius: 50px; font-size: 0.9rem; color: var(--text-light); margin-top: 10px; font-weight: 500; }
.info-header { padding: 20px 30px; border-bottom: 1px solid #f0f2f5; display: flex; align-items: center; gap: 12px; }
.info-header h5 { margin: 0; font-weight: 700; font-size: 1.1rem; }
.info-body { padding: 25px 30px; }
.info-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 0.95rem; }
.info-row:last-child { margin-bottom: 0; }
.text-label { color: var(--text-light); }
.text-value { font-weight: 600; text-align: right; }
.status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; }
.badge-pending { background: #fff8e1; color: #f57c00; }
.badge-paid { background: #e8f5e9; color: #2e7d32; }
.badge-shipping { background: #e3f2fd; color: #1976d2; }
.product-item { display: flex; align-items: center; padding: 20px 30px; border-bottom: 1px solid #f0f2f5; }
.product-item:last-child { border-bottom: none; }
.thumb-img { width: 70px; height: 70px; border-radius: 12px; object-fit: cover; border: 1px solid #eee; }
.prod-details { flex: 1; padding: 0 20px; }
.prod-price { font-weight: 700; color: var(--primary); font-size: 1.1rem; }
.star-rating i { font-size: 20px; color: #e0e0e0; cursor: pointer; transition: color 0.2s; margin-right: 2px; }
.star-rating i.active { color: #ffc107; }
.review-area { background: #fafafa; padding: 15px; border-radius: 12px; margin-top: 10px; }
.summary-box { background: #fcfcfc; padding: 20px 30px; border-top: 1px dashed #eee; }
.btn-action { padding: 12px 30px; border-radius: 12px; font-weight: 600; transition: transform 0.2s; }
.btn-action:hover { transform: translateY(-2px); }
</style>
<div class="checkout-wrapper">

    <div class="harmony-card fade-up">
        <div class="success-banner">
            <div class="success-icon-box">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 class="fw-bold text-dark mb-2">{{ $pageTitle }}</h2>
            <p class="text-secondary mb-3">Cảm ơn bạn đã lựa chọn AquaShop.</p>
            <div class="order-pill">
                Mã đơn: <span class="text-dark fw-bold">#{{ $order->id }}</span>
                <span class="mx-2">•</span>
                {{ $order->created_at ? $order->created_at->format('H:i - d/m/Y') : now()->format('H:i - d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="harmony-card fade-up h-100">
                <div class="info-header">
                    <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                    <h5>Thông tin nhận hàng</h5>
                </div>
                <div class="info-body">
                    <div class="info-row">
                        <span class="text-label">Người nhận</span>
                        <span class="text-value">{{ $displayName }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-label">Điện thoại</span>
                        <span class="text-value">{{ $displayPhone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-label">Địa chỉ</span>
                        <span class="text-value text-break w-50">{{ $displayAddress }}</span>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="info-row align-items-center">
                            <span class="text-label">Trạng thái đơn</span>
                            <span class="status-badge bg-info text-white">{{ $order->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="harmony-card fade-up h-100">
                <div class="info-header">
                    <i class="bi bi-wallet2 text-primary fs-5"></i>
                    <h5>Thanh toán & Vận chuyển</h5>
                </div>
                <div class="info-body">
                    @php
                        $payMethodMap = ['cod'=>'COD (Tiền mặt)', 'online'=>'Chuyển khoản / Ví'];
                        $payStatusMap = ['pending'=>'Chờ xử lý', 'paid'=>'Đã thanh toán', 'failed'=>'Thất bại'];
                        $shipStatusMap = ['pending'=>'Chờ lấy hàng', 'shipping'=>'Đang giao hàng', 'delivered'=>'Đã giao', 'cancelled'=>'Đã hủy'];
                        
                        $isPaid = ($payment->status ?? '') == 'paid';
                    @endphp

                    <div class="info-row">
                        <span class="text-label">Hình thức</span>
                        <span class="text-value">{{ $payMethodMap[$payment->method ?? 'cod'] ?? $payment->method }}</span>
                    </div>
                    
                    <div class="info-row align-items-center">
                        <span class="text-label">Thanh toán</span>
                        <div class="text-end">
                            <span class="status-badge {{ $isPaid ? 'badge-paid' : 'badge-pending' }}">
                                {{ $payStatusMap[$payment->status ?? 'pending'] ?? 'Chờ xử lý' }}
                            </span>
                            @if(in_array($payment->method ?? '', ['online','momo']) && !$isPaid)
                                <div class="mt-1">
                                    <a href="{{ route('payment.momo.mock', $order->id) }}" class="small text-primary text-decoration-none fst-italic">
                                        (Demo: Kích hoạt đã TT)
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <div class="info-row">
                            <span class="text-label">Vận chuyển</span>
                            <div class="text-end">
                                <span class="fw-bold text-primary">
                                    {{ $shipStatusMap[$shipment->status ?? 'pending'] ?? 'Đang xử lý' }}
                                </span>
                                @if($shipment->tracking_code ?? null)
                                    <div class="small text-muted font-monospace mt-1">#{{ $shipment->tracking_code }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $payMethod = $payment ? strtolower($payment->method ?? 'cod') : 'cod';
        $payStatus = $payment ? ($payment->status ?? 'pending') : 'pending';
        $needPayQr = $payment && in_array($payMethod, ['online', 'momo']) && $payStatus === 'pending';
        $deadline = optional($order->created_at)->addMinutes(15);
    @endphp

    @if($needPayQr)
    <div class="harmony-card fade-up mt-4">
        <div class="info-header bg-danger bg-opacity-10 text-danger border-0">
            <i class="bi bi-qr-code-scan"></i>
            <h5 class="text-danger">Yêu cầu thanh toán</h5>
            <div id="qrCountdown" data-deadline="{{ $deadline }}" class="ms-auto fw-bold bg-white px-3 py-1 rounded shadow-sm text-dark small">
                15:00
            </div>
        </div>
        <div class="p-4">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <p class="mb-2">Quét mã để thanh toán đơn hàng. Giao dịch sẽ tự động hủy nếu quá hạn.</p>
                    <div class="d-flex flex-column gap-2 text-secondary small">
                        <span><i class="bi bi-phone me-2"></i>MoMo: <strong>0332643954</strong></span>
                        <span><i class="bi bi-chat-left-text me-2"></i>Nội dung: <strong>{{ $notePhone ?? ($order->phone ?? '') ?: '...' }}</strong></span>
                        <span><i class="bi bi-cash-coin me-2"></i>Số tiền: <strong class="text-danger fs-6">{{ number_format((int)($order->total ?? 0)) }} đ</strong></span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @php
                        $momoAmount = (int) ($order->total ?? 0);
                        $qrContent = $momoQrUrl ?: ($momoPayUrl ?? "momo://app?action=payWithApp&phone=0332643954&amount={$momoAmount}&comment=Thanh toan don {$order->id}");
                        $qrEncoded = urlencode($qrContent);
                    @endphp
                    <div class="d-inline-block p-2 border rounded bg-white">
                         @if(!empty($momoQrUrl))
                            <img src="{{ $momoQrUrl }}" alt="MoMo QR" width="140" class="img-fluid">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ $qrEncoded }}" alt="MoMo QR" width="140" class="img-fluid">
                        @endif
                    </div>
                    <div class="mt-2 text-danger fw-bold small">Quét bằng MoMo</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="harmony-card fade-up mt-4">
        <div class="info-header">
            <i class="bi bi-bag-check text-primary fs-5"></i>
            <h5>Chi tiết đơn hàng</h5>
        </div>
        
        <div>
            @foreach($items as $it)
            <div class="product-item flex-column flex-md-row align-items-start align-items-md-center">
                <div class="d-flex align-items-center mb-3 mb-md-0" style="flex: 2;">
                    <img src="{{ asset('assets/img/products/'.($it->product_image ?? 'default.png')) }}" class="thumb-img shadow-sm">
                    <div class="ms-3">
                        <div class="fw-bold text-dark">{{ $it->product_name }}</div>
                        <div class="text-muted small mt-1">Phân loại: {{ $it->variant ?? 'Tiêu chuẩn' }}</div>
                        <div class="d-md-none fw-bold text-primary mt-1">{{ number_format($it->price) }} đ x {{ $it->quantity }}</div>
                    </div>
                </div>

                <div class="d-none d-md-block text-center text-muted" style="flex: 1;">
                    x{{ $it->quantity }}
                </div>
                <div class="d-none d-md-block text-end" style="flex: 1;">
                    <div class="prod-price">{{ number_format($it->price * $it->quantity) }} đ</div>
                </div>

                <div class="w-100 w-md-auto ms-md-4 mt-3 mt-md-0" style="flex: 1.5; min-width: 220px;">
                    @php
                        $statusCode = \App\Models\Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status);
                        $isCompleted = $statusCode === 'completed'; 
                        $userSession = session('admin');
                        $userId = $userSession ? $userSession->id : null;
                        $hasReviewed = $userId ? \App\Models\Review::where('product_id', $it->product_id)
                                                        ->where('user_id', $userId)
                                                        ->where('order_id', $order->id)
                                                        ->exists() : false;
                    @endphp

                    @if($isCompleted && !$hasReviewed)
                        <div class="review-area">
                            <form method="POST" action="{{ route('product.review.add', $it->product_id) }}" class="review-form">
                                @csrf
                                <input type="hidden" name="rating" class="rating-input" value="5">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-secondary">Đánh giá:</span>
                                    <div class="star-rating">
                                        @for($i=1; $i<=5; $i++) <i class="bi bi-star-fill active" data-value="{{ $i }}"></i> @endfor
                                    </div>
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="content" class="form-control border-0" placeholder="Nhập nhận xét..." required style="box-shadow: none; background: #fff;">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
                                </div>
                            </form>
                        </div>
                    @elseif($hasReviewed)
                        <div class="text-md-end text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Đã đánh giá</div>
                    @else
                        <div class="text-md-end text-muted small fst-italic">Chờ hoàn tất để đánh giá</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="summary-box">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fw-medium">Tổng tiền thanh toán</span>
                <span class="fs-4 fw-bold text-danger">{{ number_format($order->total) }} đ</span>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mt-5 mb-5 fade-up">
        <a href="{{ route('home') }}" class="btn btn-light btn-action border shadow-sm text-secondary">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
        
        @if(strtolower($order->status) === 'shipping')
            <form method="POST" action="{{ route('orders.received', $order->id) }}" onsubmit="return confirm('Bạn xác nhận đã nhận được kiện hàng này?')">
                @csrf
                <button class="btn btn-primary btn-action shadow-sm">
                    <i class="bi bi-box-seam me-2"></i>Đã nhận được hàng
                </button>
            </form>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Animation Fade In
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // 2. Countdown Timer
    const timerEl = document.getElementById('qrCountdown');
    if (timerEl && timerEl.dataset.deadline) {
        const deadline = new Date(timerEl.dataset.deadline).getTime();
        const tick = () => {
            const now = new Date().getTime();
            const diff = deadline - now;
            if (diff <= 0) {
                timerEl.innerHTML = 'Hết hạn';
                timerEl.className = 'ms-auto fw-bold bg-secondary text-white px-3 py-1 rounded shadow-sm small';
                return;
            }
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);
            timerEl.innerHTML = `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            requestAnimationFrame(tick);
        };
        tick();
    }

    // 3. Star Rating Handler
    document.querySelectorAll('.review-form').forEach(form => {
        const stars = form.querySelectorAll('.star-rating i');
        const input = form.querySelector('.rating-input');

        stars.forEach(star => {
            star.addEventListener('mouseover', function(){
                const val = this.dataset.value;
                stars.forEach(s => {
                    s.classList.toggle('active', s.dataset.value <= val);
                    s.classList.toggle('bi-star-fill', s.dataset.value <= val);
                    s.classList.toggle('bi-star', s.dataset.value > val);
                });
            });
            star.addEventListener('click', function(){
                input.value = this.dataset.value;
            });
        });

        form.querySelector('.star-rating').addEventListener('mouseleave', function(){
            const currentVal = input.value;
            stars.forEach(s => {
                const isActive = s.dataset.value <= currentVal;
                s.classList.toggle('active', isActive);
                s.className = isActive ? 'bi bi-star-fill active' : 'bi bi-star';
            });
        });

        form.addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(form);
            const url = form.getAttribute('action');

            fetch(url, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .catch(() => location.reload())
            .then(data => {
                if (data && data.success) {
                    form.parentElement.innerHTML = '<div class="text-md-end text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Đã gửi đánh giá</div>';
                }
            });
        });
    });
});
</script>

@endsection
