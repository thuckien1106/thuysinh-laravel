@extends('layouts.header')
@section('title', 'Thanh toán')
@section('content')

<style>
/* ===================== Fade-in ===================== */
.fade-in { opacity: 0; transform: translateY(18px); transition: .55s ease; }
.fade-in.visible { opacity: 1; transform: translateY(0); }

/* ===================== Premium Card ===================== */
.checkout-box {
  background: rgba(255,255,255,0.85);
  padding: 40px;
  border-radius: 28px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  border: 1px solid rgba(255,255,255,0.55);
  backdrop-filter: blur(12px);
  margin-bottom: 32px;
}

/* ===================== Heading ===================== */
.checkout-title {
  font-size: 28px; font-weight: 800;
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

/* ===================== Inputs ===================== */
.form-control-lg, .form-select-lg { border-radius: 14px; padding: 12px 16px; font-size: 15px; }
.form-control-lg:focus, .form-select-lg:focus { box-shadow: 0 0 0 4px rgba(0,150,136,0.22); border-color: #009688; }

/* ===================== Ocean Buttons ===================== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  background-size: 200% 200%; border: none; color: white !important;
  font-weight: 700; border-radius: 16px; padding: 12px 28px; transition: .35s ease; width: 100%;
}
.btn-ocean:hover { background-position: right; transform: translateY(-2px); box-shadow: 0 8px 22px rgba(0,150,136,0.35); }

.btn-outline-ocean {
  border: 2px solid #009688; color: #009688; font-weight: 600;
  border-radius: 12px; transition: .3s ease;
}
.btn-outline-ocean:hover { background:#009688; color:white; }

/* ===================== Summary Box ===================== */
.summary-box {
  background: #f7fffe; border: 1px solid #e0f2f1;
  border-radius: 18px; padding: 20px;
  position: sticky; top: 20px; /* Để cột bên phải trượt theo khi cuộn */
}
.summary-table th { font-size: 13px; color: #666; font-weight: 600; }
.summary-table td { font-size: 14px; vertical-align: middle; }
.total-row { border-top: 2px dashed #b2dfdb; margin-top: 15px; padding-top: 15px; }
.qr-pay { display: none; }
.qr-pay.show { display: block; }
.qr-card {
  border: 1px solid #e0e0e0; border-radius: 14px;
  padding: 12px; background: #fff;
}
</style>

<div class="container mt-5">
<section class="checkout-box fade-in">
  
  <h2 class="checkout-title mb-4">Thanh toán đơn hàng</h2>

  {{-- ===== ERRORS & SUCCESS MESSAGES ===== --}}
  @if(session('saved_coupon'))
    @php $sc = strtoupper(session('saved_coupon')['code']); @endphp
    <div class="alert alert-success d-flex justify-content-between align-items-center rounded-4 shadow-sm mb-4">
      <div>🎉 Mã <strong>{{ $sc }}</strong> đã được lưu.</div>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger rounded-3 mb-4">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  @php
    $items = $cart ?? [];
    $subtotal = $total ?? 0;
    $coupon = session('coupon');
    $discount = 0;
    if ($coupon) {
        if (($coupon['type'] ?? '') === 'percent') {
            $discount = round($subtotal * (($coupon['value'] ?? 0) / 100));
        } elseif (($coupon['type'] ?? '') === 'fixed') {
            $discount = (int) ($coupon['value'] ?? 0);
        }
    }
    $grand = max(0, $subtotal - $discount);
  @endphp

  <div class="row g-5">
    
    {{-- ================= LEFT COLUMN: INPUT FORM ================= --}}
    <div class="col-lg-8">
      <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        
        <div class="mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-person-lines-fill me-2"></i>Thông tin giao hàng</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Họ tên</label>
                    <input type="text" name="customer_name" class="form-control form-control-lg"
                           value="{{ old('customer_name', $prefill->full_name ?? '') }}" required placeholder="Nhập họ tên">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Số điện thoại</label>
                    <input type="text" name="customer_phone" class="form-control form-control-lg"
                           placeholder="090..." value="{{ old('customer_phone', $prefill->phone ?? '') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small text-muted">Địa chỉ chi tiết</label>
                    <input type="text" name="customer_address" class="form-control form-control-lg"
                           placeholder="Số nhà, tên đường..."
                           value="{{ old('customer_address', $prefill->address ?? '') }}" required>
                </div>

                {{-- Location Selects: Đã thêm name attribute --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Tỉnh/Thành</label>
                    <select class="form-select form-select-lg" name="province" id="co_province" data-selected="{{ old('province', $prefill->province ?? '') }}" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Quận/Huyện</label>
                    <select class="form-select form-select-lg" name="district" id="co_district" data-selected="{{ old('district', $prefill->district ?? '') }}" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Phường/Xã</label>
                    <select class="form-select form-select-lg" name="ward" id="co_ward" data-selected="{{ old('ward', $prefill->ward ?? '') }}" required></select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-credit-card-2-front me-2"></i>Phương thức thanh toán</h5>
            <div class="p-3 rounded-4 border bg-white">
              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" id="pm_cod"
                       value="cod" {{ old('payment_method','cod')=='cod' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="pm_cod">
                  Thanh toán khi nhận hàng (COD)
                </label>
                <div class="small text-muted ms-2">Bạn sẽ thanh toán tiền mặt khi shipper giao hàng đến.</div>
              </div>
              <hr class="my-2 op-50">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pm_online"
                       value="online" {{ old('payment_method')=='online' ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="pm_online">
                  Thanh toán trực tuyến (VNPAY / QR)
                </label>
              </div>
            </div>
        </div>

        {{-- QR PAYMENTS (hi��n khi ch��n thanh toA�n online) --}}
        <div id="qrPayBox" class="qr-pay mt-3">
          <div class="alert alert-info py-2 px-3 mb-3 small">
            Qu��t mA� thanh toA�n Online. S��� tiA�n: <strong>{{ number_format($grand) }} �`</strong>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="qr-card text-center h-100">
                <div class="fw-semibold mb-2">MoMo</div>
                <img class="img-fluid rounded" alt="QR MoMo"
                     src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScyMjAnIGhlaWdodD0nMjIwJz48cmVjdCB3aWR0aD0nMjIwJyBoZWlnaHQ9JzIyMCcgZmlsbD0nI2Y0ZDRmZicvPjxyZWN0IHg9JzE2JyB5PScxNicgd2lkdGg9JzE4OCcgaGVpZ2h0PScxODgnIHJ4PScxMicgZmlsbD0nd2hpdGUnLz48dGV4dCB4PScxMTAnIHk9JzExMCcgdGV4dC1hbmNob3I9J21pZGRsZScgZG9taW5hbnQtYmFzZWxpbmU9J21pZGRsZScgZm9udC1mYW1pbHk9J0FyaWFsJyBmb250LXNpemU9JzI2JyBmaWxsPScjYjAwMDZkJz5Nb01vIFFSPC90ZXh0Pjwvc3ZnPg==">
                <div class="small text-muted mt-2">N��i dung: T��n + S�� �`i���n tho���i</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="qr-card text-center h-100">
                <div class="fw-semibold mb-2">VNPAY</div>
                <img class="img-fluid rounded" alt="QR VNPAY"
                     src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScyMjAnIGhlaWdodD0nMjIwJz48cmVjdCB3aWR0aD0nMjIwJyBoZWlnaHQ9JzIyMCcgZmlsbD0nI2Q4ZjVmZicvPjxyZWN0IHg9JzE2JyB5PScxNicgd2lkdGg9JzE4OCcgaGVpZ2h0PScxODgnIHJ4PScxMicgZmlsbD0nd2hpdGUnLz48dGV4dCB4PScxMTAnIHk9JzExMCcgdGV4dC1hbmNob3I9J21pZGRsZScgZG9taW5hbnQtYmFzZWxpbmU9J21pZGRsZScgZm9udC1mYW1pbHk9J0FyaWFsJyBmb250LXNpemU9JzI2JyBmaWxsPScjMDA2NmNjJz5WTlBBWTwvdGV4dD48L3N2Zz4=">
                <div class="small text-muted mt-2">Qu��t mA� tr��n app VNPAY/Ng��n hA�ng</div>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>

    {{-- ================= RIGHT COLUMN: SUMMARY & COUPON ================= --}}
    <div class="col-lg-4">
      <div class="summary-box shadow-sm">
        <h5 class="fw-bold mb-3">Đơn hàng của bạn</h5>
        
        {{-- ITEMS LIST --}}
        @php
            $items = $cart ?? [];
            $subtotal = $total ?? 0;
            $coupon = session('coupon');
            $discount = 0;
            if ($coupon) {
                if (($coupon['type'] ?? '') === 'percent') {
                    $discount = round($subtotal * (($coupon['value'] ?? 0) / 100));
                } elseif (($coupon['type'] ?? '') === 'fixed') {
                    $discount = (int) ($coupon['value'] ?? 0);
                }
            }
            $grand = max(0, $subtotal - $discount);
        @endphp

        @if(empty($items))
            <div class="text-muted text-center py-3">Giỏ hàng trống</div>
        @else
            <div style="max-height: 250px; overflow-y: auto; margin-bottom: 15px;">
                <table class="table table-borderless summary-table mb-0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-end">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr>
                            <td>
                                <div class="text-truncate" style="max-width: 160px;">{{ $it['name'] }}</div>
                                <small class="text-muted">x {{ $it['quantity'] }}</small>
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ number_format(($it['price'] ?? 0) * ($it['quantity'] ?? 0)) }}đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- FORM COUPON (Độc lập, không lồng trong checkoutForm) --}}
        <div class="mb-3">
            <form action="{{ route('cart.coupon') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="code" class="form-control" placeholder="Mã giảm giá" 
                       value="{{ session('coupon') ? session('coupon')['code'] : (session('saved_coupon')['code'] ?? '') }}">
                <button class="btn btn-outline-ocean px-3 text-nowrap">Áp dụng</button>
            </form>
            @if(session('coupon'))
                 <div class="small text-success mt-1"><i class="bi bi-check-circle-fill"></i> Đã áp dụng mã: {{ session('coupon')['code'] }}</div>
            @endif
        </div>

        {{-- TOTALS --}}
        <div class="total-row">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Tạm tính</span>
                <span class="fw-semibold">{{ number_format($subtotal) }} đ</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Giảm giá</span>
                <span class="text-success fw-bold">-{{ number_format($discount) }} đ</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <span class="fw-bold fs-5 text-dark">Tổng cộng</span>
                <span class="fw-bold fs-4 text-danger">{{ number_format($grand) }} đ</span>
            </div>
        </div>

        {{-- SUBMIT BUTTON (Liên kết với form bên trái bằng thuộc tính form="") --}}
        <button type="submit" form="checkoutForm" class="btn btn-ocean mt-4 shadow-sm">
            Xác nhận đặt hàng
        </button>
      </div>
    </div>

  </div>
</section>
</div>

@include('layouts.footer')

{{-- ===================== JS ===================== --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".fade-in").forEach(el => el.classList.add("visible"));

  const qrBox = document.getElementById('qrPayBox');
  const online = document.getElementById('pm_online');
  const cod = document.getElementById('pm_cod');
  const toggleQr = () => {
    if (!qrBox) return;
    qrBox.classList.toggle('show', online && online.checked);
  };
  [online, cod].forEach(el => el && el.addEventListener('change', toggleQr));
  toggleQr();
});
</script>

{{-- ========== TỈNH/QUẬN/PHƯỜNG ========== --}}
<script>
document.addEventListener('DOMContentLoaded', async function(){
  const P = document.getElementById('co_province');
  const D = document.getElementById('co_district');
  const W = document.getElementById('co_ward');
  if (!P || !D || !W) return;

  const selP = P.dataset.selected || '';
  const selD = D.dataset.selected || '';
  const selW = W.dataset.selected || '';

  const opt = (v,t,s)=>{
      const o=document.createElement('option');
      o.value=v; // Giá trị gửi về server là Tên Tỉnh (do API trả về name)
      o.textContent=t;
      if(s && v===s) o.selected=true;
      return o
  };
  const reset=(el,ph)=>{el.innerHTML='';el.appendChild(opt('',ph,''));};

  try {
    let data;
    // Ưu tiên gọi API, nếu lỗi thì dùng file local
    try {
      const r = await fetch('https://provinces.open-api.vn/api/?depth=3');
      if (!r.ok) throw new Error();
      const arr = await r.json();
      // Map dữ liệu API về cấu trúc chuẩn
      data = { provinces: arr.map(p => ({ name:p.name, districts:(p.districts||[]).map(d=>({ name:d.name, wards:(d.wards||[]).map(w=>({ name:w.name })) })) })) };
    } catch(e){
      const res = await fetch("{{ asset('assets/data/vn_admin.json') }}");
      data = await res.json();
    }

    reset(P,'Chọn Tỉnh/Thành');
    data.provinces.forEach(pr=>P.appendChild(opt(pr.name,pr.name,selP)));

    const applyD=()=>{
      const pr = data.provinces.find(x=>x.name===P.value);
      reset(D,'Chọn Quận/Huyện'); reset(W,'Chọn Phường/Xã');
      if(!pr)return; 
      pr.districts.forEach(di=>D.appendChild(opt(di.name,di.name,selD)));
      if(selD && pr.districts.find(x=>x.name===selD)) applyW();
    };

    const applyW=()=>{
      const pr=data.provinces.find(x=>x.name===P.value);
      if(!pr)return;
      const di=pr.districts.find(x=>x.name===D.value);
      reset(W,'Chọn Phường/Xã');
      if(!di)return; 
      di.wards.forEach(wa=>W.appendChild(opt(wa.name,wa.name,selW)));
    };

    P.addEventListener('change', applyD);
    D.addEventListener('change', applyW);
    
    // Khởi chạy lần đầu nếu có dữ liệu cũ (old input)
    if(selP) applyD();
  } catch(e){
      console.error("Lỗi tải địa chính:", e);
  }
});
</script>

@endsection
