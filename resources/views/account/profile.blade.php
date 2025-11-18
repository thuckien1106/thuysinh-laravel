@extends('layouts.header')
@section('title','Tài khoản của tôi')
@section('content')

<style>
/* ======================== PREMIUM ACCOUNT PAGE =========================== */

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

/* Section box */
.section-box {
  background: #ffffff;
  border-radius: 22px;
  padding: 28px;
  box-shadow: 0 10px 28px rgba(0,0,0,0.08);
  border: 1px solid rgba(0,0,0,.05);
  margin-bottom: 25px;
}

/* Labels */
.form-label {
  font-weight: 600;
  color: #495057;
}

/* Inputs */
.form-control,
.form-select {
  border-radius: 14px;
  padding: 12px 14px;
  box-shadow: inset 0 0 4px rgba(0,0,0,0.05);
  border: 1px solid #d7dce3;
}

.form-control:focus,
.form-select:focus {
  border-color: #00bfa5;
  box-shadow: 0 0 0 3px rgba(0,150,136,.25);
}

/* Title */
.section-title {
  font-size: 22px;
  font-weight: 800;
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Alert custom */
.alert-premium {
  background: #e8f7ff;
  border-left: 4px solid #0d6efd;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 15px;
}

.alert-error {
  background: #fdecec !important;
  border-left: 4px solid #dc3545;
  color: #c62828;
}

/* Button */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5);
  color: #fff !important;
  font-weight: 600;
  border-radius: 14px;
  padding: 10px 26px;
  transition: .25s;
}
.btn-ocean:hover {
  transform: translateY(-2px);
  color: #fff !important;
}
</style>


<div class="fade-appear">

  <div class="section-box">
    <h4 class="section-title mb-3">Thông tin tài khoản & địa chỉ nhận hàng</h4>

    {{-- ERROR --}}
    @if($errors->any())
      <div class="alert-premium alert-error mb-3">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- Checkout notice --}}
    @if(request('from')==='checkout')
      <div class="alert-premium mb-3">
        Vui lòng điền đầy đủ thông tin để tiếp tục thanh toán.
      </div>
    @endif

    <form method="POST" action="{{ route('account.profile.save') }}">
      @csrf

      @if(request('from')==='checkout')
        <input type="hidden" name="from" value="checkout">
      @endif

      <!-- ==================== PERSONAL INFO ==================== -->
      <h5 class="fw-bold mt-2 mb-3"><i class="bi bi-person-circle me-2 text-primary"></i>Thông tin cá nhân</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Họ tên @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
          <input type="text" class="form-control"
            name="full_name"
            value="{{ old('full_name', $customer->full_name ?? '') }}"
            placeholder="Nguyễn Văn A"
            @if(request('from')==='checkout') required @endif>
        </div>

        <div class="col-md-6">
          <label class="form-label">Số điện thoại @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
          <input type="text" class="form-control"
            name="phone"
            value="{{ old('phone', $customer->phone ?? '') }}"
            placeholder="090..."
            @if(request('from')==='checkout') required @endif>
        </div>

        <div class="col-md-6">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày sinh</label>
          <input type="date" class="form-control"
            name="birthdate"
            value="{{ old('birthdate', $customer->birthday ?? '') }}">
        </div>
      </div>

      <hr class="my-4">

      <!-- ==================== SHIPPING ADDRESS ==================== -->
      <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Địa chỉ nhận hàng</h5>
      <div class="row g-3">

        <div class="col-md-12">
          <label class="form-label">Địa chỉ chi tiết @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
          <input type="text" class="form-control"
            name="address_line"
            value="{{ old('address_line', $address->address_line ?? ($customer->address ?? '')) }}"
            placeholder="Số nhà, đường..."
            @if(request('from')==='checkout') required @endif>
        </div>

        <div class="col-md-4">
          <label class="form-label">Tỉnh/Thành</label>
          <select class="form-select" id="provinceSelect" name="province"
            data-selected="{{ old('province', $address->province ?? '') }}"></select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Quận/Huyện</label>
          <select class="form-select" id="districtSelect" name="district"
            data-selected="{{ old('district', $address->district ?? '') }}"></select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Phường/Xã</label>
          <select class="form-select" id="wardSelect" name="ward"
            data-selected="{{ old('ward', $address->ward ?? '') }}"></select>
        </div>

      </div>

      <button class="btn btn-ocean mt-4">Lưu thông tin</button>

    </form>
  </div>

</div>


<!-- ===================== JS: LOCATION PICKER ===================== -->
<script>
document.addEventListener('DOMContentLoaded', async function () {
  const P = document.getElementById('provinceSelect');
  const D = document.getElementById('districtSelect');
  const W = document.getElementById('wardSelect');
  if (!P || !D || !W) return;

  const selP = P.dataset.selected || '';
  const selD = D.dataset.selected || '';
  const selW = W.dataset.selected || '';

  const opt = (v,t,s)=>{const o=document.createElement('option');o.value=v;o.textContent=t;if(s&&v===s)o.selected=true;return o};
  const reset=(el,ph)=>{el.innerHTML='';el.appendChild(opt('',ph,''));};

  try {
    let data;

    // ưu tiên API public
    try {
      const res = await fetch('https://provinces.open-api.vn/api/?depth=3');
      const arr = await res.json();
      data = {
        provinces: arr.map(p => ({
          name: p.name,
          districts: (p.districts||[]).map(d => ({
            name: d.name,
            wards: (d.wards||[]).map(w => ({name: w.name}))
          }))
        }))
      };
    } catch(err) {
      // fallback json local
      const res = await fetch('{{ asset("assets/data/vn_admin.json") }}');
      data = await res.json();
    }

    // provinces
    reset(P,'Chọn tỉnh/thành');
    data.provinces.forEach(pr => P.appendChild(opt(pr.name,pr.name,selP)));

    const applyD = ()=>{
      const pv = P.value;
      const pr = data.provinces.find(x=>x.name===pv);
      reset(D,'Chọn quận/huyện'); reset(W,'Chọn phường/xã');
      if(!pr) return;
      pr.districts.forEach(di => D.appendChild(opt(di.name,di.name,selD)));
      if(selD) applyW();
    };

    const applyW = ()=>{
      const pr = data.provinces.find(x=>x.name===P.value);
      reset(W,'Chọn phường/xã');
      if(!pr) return;
      const di = pr.districts.find(x=>x.name===D.value);
      if(!di) return;
      di.wards.forEach(w => W.appendChild(opt(w.name,w.name,selW)));
    };

    P.addEventListener('change', applyD);
    D.addEventListener('change', applyW);
    applyD();

  } catch(e){}
});
</script>


<script>
// fade in
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".fade-appear").forEach(el => {
    setTimeout(() => { el.classList.add("visible"); }, 150);
  });
});
</script>

@endsection
