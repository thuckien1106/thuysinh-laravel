@extends('layouts.header')
@section('title', 'Thanh toán')
@section('content')

<section class="bg-white p-5 rounded-4 shadow-sm">
  <h2 class="fw-bold text-primary mb-4">Thanh toán đơn hàng</h2>
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('checkout.process') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Họ tên</label>
        <input type="text" name="customer_name" class="form-control form-control-lg" value="{{ old('customer_name', $prefill->full_name ?? '') }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Số điện thoại (tuỳ chọn)</label>
        <input type="text" name="customer_phone" class="form-control form-control-lg" placeholder="090..." value="{{ old('customer_phone', $prefill->phone ?? '') }}">
      </div>
      <div class="col-12">
        <label class="form-label">Địa chỉ giao hàng</label>
        <input type="text" name="customer_address" class="form-control form-control-lg" value="{{ old('customer_address', $prefill->address ?? '') }}" placeholder="Số nhà, đường, ..." required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tỉnh/Thành</label>
        <select class="form-select form-select-lg" id="co_province" data-selected="{{ old('province', $prefill->province ?? '') }}"></select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Quận/Huyện</label>
        <select class="form-select form-select-lg" id="co_district" data-selected="{{ old('district', $prefill->district ?? '') }}"></select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Phường/Xã</label>
        <select class="form-select form-select-lg" id="co_ward" data-selected="{{ old('ward', $prefill->ward ?? '') }}"></select>
      </div>
      <div class="col-12">
        <label class="form-label">Phương thức thanh toán</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cod" {{ old('payment_method','cod')=='cod' ? 'checked' : '' }}>
          <label class="form-check-label" for="pm_cod">Thanh toán khi nhận hàng (COD)</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="pm_online" value="online" {{ old('payment_method')=='online' ? 'checked' : '' }}>
          <label class="form-check-label" for="pm_online">Thanh toán trực tuyến (demo)</label>
        </div>
        <div class="form-text">Demo: chọn Trực tuyến sẽ tự đánh dấu đã thanh toán.</div>
      </div>
      <div class="col-12 text-end mt-4">
        <button class="btn btn-ocean px-5">Xác nhận đặt hàng</button>
      </div>
    </div>
  </form>
</section>

@include('layouts.footer')
@endsection

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
    // Prefer public API; fallback to local JSON
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
    reset(P,'Chọn tỉnh/thành'); data.provinces.forEach(pr=>P.appendChild(opt(pr.name, pr.name, selP)));
    const applyD=()=>{const pr=data.provinces.find(x=>x.name===P.value); reset(D,'Chọn quận/huyện'); reset(W,'Chọn phường/xã'); if(!pr)return; pr.districts.forEach(di=>D.appendChild(opt(di.name, di.name, selD))); if(selD) applyW(); };
    const applyW=()=>{const pr=data.provinces.find(x=>x.name===P.value); reset(W,'Chọn phường/xã'); if(!pr)return; const di=pr.districts.find(x=>x.name===D.value); if(!di)return; di.wards.forEach(wa=>W.appendChild(opt(wa.name, wa.name, selW))); };
    P.addEventListener('change', applyD); D.addEventListener('change', applyW);
    applyD();
  } catch(e) { /* ignore if data file missing */ }
});
</script>
