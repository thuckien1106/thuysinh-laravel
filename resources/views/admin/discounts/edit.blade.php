@extends('layouts.admin')
@section('title','Sửa giảm giá')
@section('content')
<div class="admin-headerbar">
  <h3>Sửa giảm giá #{{ $discount->id }}</h3>
  <div><a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-ocean">Quay lại</a></div>
</div>

<div class="card p-3">
  <form method="POST" action="{{ route('admin.discounts.update', $discount->id) }}" class="row g-3">
    @csrf @method('PUT')
    <div class="col-md-6">
      <label class="form-label">Sản phẩm</label>
      <select name="product_id" class="form-select" required>
        @foreach($products as $p)
          <option value="{{ $p->id }}" @if($p->id==$discount->product_id) selected @endif>{{ $p->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Phần trăm</label>
      <input type="number" min="1" max="90" name="percent" class="form-control" value="{{ $discount->percent }}" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Ghi chú</label>
      <input type="text" name="note" class="form-control" value="{{ $discount->note }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Bắt đầu</label>
      <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at', optional($discount->start_at)->format('Y-m-d\\TH:i')) }}" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Kết thúc</label>
      <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at', optional($discount->end_at)->format('Y-m-d\\TH:i')) }}" required>
    </div>
    <div class="col-12">
      <button class="btn btn-ocean">Cập nhật</button>
    </div>
  </form>
</div>
@endsection
