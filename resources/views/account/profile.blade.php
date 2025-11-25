@extends('layouts.header')
@section('title','Tài khoản của tôi')
@section('content')

<style>
/* ======================== MODERN PROFILE UI =========================== */
:root {
    --primary-color: #009688;
    --primary-light: #e0f2f1;
    --text-dark: #344767;
    --text-muted: #7b809a;
    --input-bg: #f8f9fa;
    --border-color: #e9ecef;
}

body {
    background-color: #f3f4f6;
}

/* Container */
.profile-container {
    max-width: 960px;
    margin: 40px auto;
    padding: 0 15px;
}

/* Animations */
.fade-up {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}
.fade-up.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Cards */
.card-modern {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    border: none;
    margin-bottom: 24px;
    overflow: hidden;
}

.card-header-modern {
    background: linear-gradient(to right, #ffffff, #f8f9fa);
    padding: 20px 25px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-title i {
    background: var(--primary-light);
    color: var(--primary-color);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin-right: 12px;
    font-size: 1.1rem;
}

.card-body-modern {
    padding: 25px;
}

/* Inputs */
.form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-group-text {
    background-color: var(--input-bg);
    border: 1px solid var(--border-color);
    border-right: none;
    color: var(--text-muted);
    border-radius: 10px 0 0 10px;
}

.form-control, .form-select {
    background-color: var(--input-bg);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 10px 15px;
    color: #495057;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 10px 10px 0;
}

.form-control:focus, .form-select:focus {
    background-color: #fff;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 150, 136, 0.15);
}

/* Alert */
.alert-modern {
    border-radius: 12px;
    background-color: #fff3cd;
    border: 1px solid #ffecb5;
    color: #664d03;
    padding: 15px;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.alert-modern i {
    font-size: 1.2rem;
    margin-right: 10px;
}

/* Buttons */
.btn-save {
    background: linear-gradient(135deg, #009688 0%, #00796b 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 6px rgba(0, 150, 136, 0.2);
    transition: transform 0.2s, box-shadow 0.2s;
    width: 100%;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 150, 136, 0.3);
    color: white;
}

@media (min-width: 768px) {
    .btn-save { width: auto; }
}
</style>

<div class="profile-container fade-up">

    <div class="mb-4">
        <h2 class="fw-bold text-dark">Hồ sơ của tôi</h2>
        <p class="text-muted">Quản lý thông tin hồ sơ và địa chỉ nhận hàng của bạn.</p>
    </div>

    {{-- Alert Notice --}}
    @if(request('from')==='checkout')
    <div class="alert-modern shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Lưu ý:</strong> Vui lòng cập nhật đầy đủ thông tin bên dưới để tiến hành thanh toán.
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('account.profile.save') }}">
        @csrf
        @if(request('from')==='checkout')
            <input type="hidden" name="from" value="checkout">
        @endif

        <div class="row g-4">
            
            <div class="col-lg-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title"><i class="bi bi-person-vcard"></i> Thông tin cá nhân</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control"
                                        name="full_name"
                                        value="{{ old('full_name', $customer->full_name ?? '') }}"
                                        placeholder="Nhập họ tên của bạn"
                                        @if(request('from')==='checkout') required @endif>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control"
                                        name="phone"
                                        value="{{ old('phone', $customer->phone ?? '') }}"
                                        placeholder="Ví dụ: 0901234567"
                                        @if(request('from')==='checkout') required @endif>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email đăng nhập <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control bg-light"
                                        name="email"
                                        value="{{ old('email', $user->email ?? '') }}"
                                        readonly required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày sinh</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" class="form-control"
                                        name="birthdate"
                                        value="{{ old('birthdate', $customer->birthday ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title"><i class="bi bi-geo-alt"></i> Địa chỉ nhận hàng</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Địa chỉ chi tiết @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                    <input type="text" class="form-control"
                                        name="address_line"
                                        value="{{ old('address_line', $address->address_line ?? ($customer->address ?? '')) }}"
                                        placeholder="Số nhà, tên đường, tòa nhà..."
                                        @if(request('from')==='checkout') required @endif>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tỉnh / Thành phố</label>
                                <select class="form-select" id="provinceSelect" name="province"
                                    data-selected="{{ old('province', $address->province ?? '') }}">
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Quận / Huyện</label>
                                <select class="form-select" id="districtSelect" name="district"
                                    data-selected="{{ old('district', $address->district ?? '') }}">
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Phường / Xã</label>
                                <select class="form-select" id="wardSelect" name="ward"
                                    data-selected="{{ old('ward', $address->ward ?? '') }}">
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-2">
            <button class="btn btn-save">
                <i class="bi bi-check2-circle me-2"></i> Lưu thay đổi
            </button>
        </div>

    </form>
</div>

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

// fade in
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".fade-up").forEach(el => {
    setTimeout(() => { el.classList.add("visible"); }, 150);
  });
});
</script>

@endsection