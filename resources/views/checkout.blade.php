@extends('layouts.header')
@section('title', 'Thanh toán')
@section('content')

<style>
/* ===================== Fade-in ===================== */
.fade-in {
  opacity: 0;
  transform: translateY(18px);
  transition: .55s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ===================== Premium Card ===================== */
.checkout-box {
  background: rgba(255,255,255,0.78);
  padding: 40px;
  border-radius: 28px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  border: 1px solid rgba(255,255,255,0.55);
  backdrop-filter: blur(12px);
  animation: fadeUp .6s ease;
}

/* ===================== Heading ===================== */
.checkout-title {
  font-size: 28px;
  font-weight: 800;
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ===================== Inputs ===================== */
.form-control-lg,
.form-select-lg {
  border-radius: 14px;
  padding: 12px 16px;
}
.form-control-lg:focus,
.form-select-lg:focus {
  box-shadow: 0 0 0 4px rgba(0,150,136,0.22);
}

/* ===================== Ocean Buttons ===================== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  background-size: 240% 240%;
  border: none;
  color: white !important;
  font-weight: 700;
  border-radius: 16px;
  padding: 12px 28px;
  transition: .35s ease;
}
.btn-ocean:hover {
  background-position: right;
  transform: translateY(-3px);
  box-shadow: 0 8px 22px rgba(0,150,136,0.35);
}

.btn-outline-ocean {
  border: 2px solid #00a690;
  color: #009688;
  font-weight: 600;
  border-radius: 16px;
  padding: 10px 20px;
  transition: .3s ease;
}
.btn-outline-ocean:hover {
  background:#009688;
  color:white;
}

/* ===================== Coupon Box ===================== */
.coupon-box {
  background: #f0fdfa;
  border: 1px solid #99f6e4;
  border-radius: 18px;
  padding: 18px;
}

/* ===================== Animation ===================== */
@keyframes fadeUp {
  from { opacity:0; transform:translateY(20px); }
  to   { opacity:1; transform:translateY(0); }
}
</style>

<section class="checkout-box fade-in">

  {{-- ===== HEADER ===== --}}
  <h2 class="checkout-title mb-4">Thanh toán đơn hàng</h2>

  {{-- ===== COUPON SAVED ===== --}}
  @if(session('saved_coupon'))
    @php $sc = strtoupper(session('saved_coupon')['code']); @endphp
    <div class="alert alert-success d-flex justify-content-between align-items-center rounded-4 shadow-sm">
      <div>
        🎉 Mã <strong>{{ $sc }}</strong> đã được lưu. Bấm <strong>Áp dụng</strong> để sử dụng.
      </div>
      <form method="POST" action="{{ route('cart.coupon') }}" class="m-0">
        @csrf
        <input type="hidden" name="code" value="{{ $sc }}">
        <button class="btn btn-ocean btn-sm px-3 py-1">Áp dụng</button>
      </form>
    </div>
  @endif

  {{-- ===== ERRORS ===== --}}
  @if($errors->any())
    <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
  @endif

  {{-- ===================== FORM ===================== --}}
  <form method="POST" action="{{ route('checkout.process') }}">
    @csrf

    <div class="row g-4">

      {{-- ================= CUSTOMER INFO ================= --}}
      <div class="col-12">
        <h5 class="fw-bold mb-2"><i class="bi bi-person-circle me-2"></i>Thông tin giao hàng</h5>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Họ tên</label>
        <input type="text" name="customer_name" class="form-control form-control-lg"
               value="{{ old('customer_name', $prefill->full_name ?? '') }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Số điện thoại</label>
        <input type="text" name="customer_phone" class="form-control form-control-lg"
               placeholder="090..." value="{{ old('customer_phone', $prefill->phone ?? '') }}">
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Địa chỉ chi tiết</label>
        <input type="text" name="customer_address"
               class="form-control form-control-lg"
               placeholder="Số nhà, tên đường..."
               value="{{ old('customer_address', $prefill->address ?? '') }}" required>
      </div>

      {{-- ================= LOCATION SELECT ================= --}}
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tỉnh/Thành</label>
        <select class="form-select form-select-lg" id="co_province" data-selected="{{ old('province', $prefill->province ?? '') }}"></select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Quận/Huyện</label>
        <select class="form-select form-select-lg" id="co_district" data-selected="{{ old('district', $prefill->district ?? '') }}"></select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Phường/Xã</label>
        <select class="form-select form-select-lg" id="co_ward" data-selected="{{ old('ward', $prefill->ward ?? '') }}"></select>
      </div>

      {{-- ================= PAYMENT METHOD ================= --}}
      <div class="col-12 mt-2">
        <h5 class="fw-bold mb-2"><i class="bi bi-wallet2 me-2"></i>Phương thức thanh toán</h5>

        <div class="p-3 rounded-3 border">
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="payment_method" id="pm_cod"
                   value="cod" {{ old('payment_method','cod')=='cod' ? 'checked' : '' }}>
            <label class="form-check-label" for="pm_cod">
              Thanh toán khi nhận hàng (COD)
            </label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" id="pm_online"
                   value="online" {{ old('payment_method')=='online' ? 'checked' : '' }}>
            <label class="form-check-label" for="pm_online">
              Thanh toán trực tuyến (demo)
            </label>
          </div>

          <div class="form-text">Demo: chọn Online sẽ tự xem như đã thanh toán.</div>
        </div>
      </div>

      {{-- ================= COUPON ================= --}}
      <div class="col-12">
        <div class="coupon-box mt-3 shadow-sm">
          <h6 class="fw-bold mb-2">
            <i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá
          </h6>

          <form method="POST" action="{{ route('cart.coupon') }}" class="row g-2">
            @csrf
            <div class="col-md-6">
              <input type="text" name="code" class="form-control form-control-lg"
                     placeholder="Nhập mã ví dụ: DATCUTELOVE"
                     value="{{ strtoupper(session('saved_coupon')['code'] ?? '') }}">
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-ocean w-100">Áp dụng</button>
            </div>
          </form>
        </div>
      </div>

      {{-- ================= SUBMIT ================= --}}
      <div class="col-12 text-end mt-4">
        <button class="btn btn-ocean px-5 py-3 fs-5">Xác nhận đặt hàng</button>
      </div>

    </div>
  </form>

</section>

@include('layouts.footer')

{{-- ===================== JS ===================== --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelector(".fade-in").classList.add("visible");
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

  const opt = (v,t,s)=>{const o=document.createElement('option');o.value=v;o.textContent=t;if(s&&v===s)o.selected=true;return o};
  const reset=(el,ph)=>{el.innerHTML='';el.appendChild(opt('',ph,''));};

  try {
    let data;
    try {
      const r = await fetch('https://provinces.open-api.vn/api/?depth=3');
      if (!r.ok) throw new Error();
      const arr = await r.json();
      data = { provinces: arr.map(p => ({ name:p.name, districts:(p.districts||[]).map(d=>({ name:d.name, wards:(d.wards||[]).map(w=>({ name:w.name })) })) })) };
    } catch(e){
      const res = await fetch('{{ asset('assets/data/vn_admin.json') }}');
      data = await res.json();
    }

    reset(P,'Chọn tỉnh/thành');
    data.provinces.forEach(pr=>P.appendChild(opt(pr.name,pr.name,selP)));

    const applyD=()=>{
      const pr = data.provinces.find(x=>x.name===P.value);
      reset(D,'Chọn quận/huyện'); reset(W,'Chọn phường/xã');
      if(!pr)return; pr.districts.forEach(di=>D.appendChild(opt(di.name,di.name,selD)));
      if(selD) applyW();
    };

    const applyW=()=>{
      const pr=data.provinces.find(x=>x.name===P.value);
      reset(W,'Chọn phường/xã');
      if(!pr)return;
      const di=pr.districts.find(x=>x.name===D.value);
      if(!di)return; di.wards.forEach(wa=>W.appendChild(opt(wa.name,wa.name,selW)));
    };

    P.addEventListener('change', applyD);
    D.addEventListener('change', applyW);
    applyD();
  } catch(e){}
});
</script>

@endsection
