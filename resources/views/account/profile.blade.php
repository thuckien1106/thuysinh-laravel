@extends('layouts.header')
@section('title','Tài khoản của tôi')
@section('content')

<div class="row g-4">
  <div class="col-lg-12">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Thông tin tài khoản & địa chỉ nhận hàng</h5>
        @if($errors->any())
          <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('account.profile.save') }}">
          @csrf
          @if(request('from')==='checkout')
            <input type="hidden" name="from" value="checkout">
            <div class="alert alert-info py-2">Vui lòng điền đầy đủ thông tin để tiếp tục thanh toán.</div>
          @endif

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Họ tên @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $customer->full_name ?? '') }}" placeholder="Nguyễn Văn A" @if(request('from')==='checkout') required @endif>
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <input type="text" class="form-control" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" placeholder="090..." @if(request('from')==='checkout') required @endif>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ngày sinh</label>
              <input type="date" class="form-control" name="birthdate" value="{{ old('birthdate', $customer->birthday ?? '') }}">
            </div>
          </div>

          <hr class="my-4">

          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">Địa chỉ nhận hàng @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <input type="text" class="form-control" name="address_line" value="{{ old('address_line', $address->address_line ?? ($customer->address ?? '')) }}" placeholder="Số nhà, đường, ..." @if(request('from')==='checkout') required @endif>
            </div>
            <div class="col-md-4">
              <label class="form-label">Tỉnh/Thành @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <select class="form-select" id="provinceSelect" name="province" data-selected="{{ old('province', $address->province ?? '') }}" @if(request('from')==='checkout') required @endif></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Quận/Huyện @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <select class="form-select" id="districtSelect" name="district" data-selected="{{ old('district', $address->district ?? '') }}" @if(request('from')==='checkout') required @endif></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Phường/Xã @if(request('from')==='checkout')<span class="text-danger">*</span>@endif</label>
              <select class="form-select" id="wardSelect" name="ward" data-selected="{{ old('ward', $address->ward ?? '') }}" @if(request('from')==='checkout') required @endif></select>
            </div>
          </div>

          <script>
          document.addEventListener('DOMContentLoaded', async function () {
            const $p = document.getElementById('provinceSelect');
            const $d = document.getElementById('districtSelect');
            const $w = document.getElementById('wardSelect');
            const selectedP = $p?.dataset.selected || '';
            const selectedD = $d?.dataset.selected || '';
            const selectedW = $w?.dataset.selected || '';
            function opt(value, text, sel) {
              const o = document.createElement('option'); o.value = value; o.textContent = text; if (sel && value===sel) o.selected = true; return o;
            }
            function reset(sel, ph) { sel.innerHTML=''; sel.appendChild(opt('', ph, '')); }
            try {
              // Try public API first, fallback to local demo JSON
              let data;
              try {
                const r = await fetch('https://provinces.open-api.vn/api/?depth=3', {mode:'cors'});
                if (!r.ok) throw new Error('API error');
                const arr = await r.json();
                data = { provinces: arr.map(p => ({ name: p.name, districts: (p.districts||[]).map(d => ({ name: d.name, wards: (d.wards||[]).map(w => ({ name: w.name })) })) })) };
              } catch (err) {
                const res = await fetch('{{ asset('assets/data/vn_admin.json') }}');
                data = await res.json();
              }
              // Provinces
              reset($p, 'Chọn tỉnh/thành');
              data.provinces.forEach(pr => $p.appendChild(opt(pr.name, pr.name, selectedP)));
              const applyDistricts = () => {
                const pv = $p.value; const pr = data.provinces.find(x => x.name===pv);
                reset($d, 'Chọn quận/huyện'); reset($w, 'Chọn phường/xã');
                if (!pr) return; pr.districts.forEach(di => $d.appendChild(opt(di.name, di.name, selectedD)));
                // if preselected district exists, also load wards
                if (selectedD) applyWards();
              };
              const applyWards = () => {
                const pv = $p.value; const dv = $d.value;
                const pr = data.provinces.find(x => x.name===pv);
                reset($w, 'Chọn phường/xã');
                if (!pr) return; const di = pr.districts.find(x => x.name===dv);
                if (!di) return; di.wards.forEach(wa => $w.appendChild(opt(wa.name, wa.name, selectedW)));
              };
              $p.addEventListener('change', applyDistricts);
              $d.addEventListener('change', applyWards);
              // Initialize
              applyDistricts();
            } catch(e) { /* silently ignore in case file missing */ }
          });
          </script>

          <button class="btn btn-ocean mt-3">Lưu</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
