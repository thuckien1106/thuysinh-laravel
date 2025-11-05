@extends('layouts.admin')
@section('title','Thêm thương hiệu')
@section('content')
<div class="admin-headerbar">
  <h3>Thêm thương hiệu</h3>
  <div><a href="{{ route('admin.brands.index') }}" class="btn btn-outline-ocean">Quay lại</a></div>
</div>

<div class="card p-3">
  <form method="POST" action="{{ route('admin.brands.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
      <label class="form-label">Tên</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Slug (tuỳ chọn)</label>
      <input type="text" name="slug" class="form-control">
    </div>
    <div class="col-12">
      <button class="btn btn-ocean">Lưu</button>
    </div>
  </form>
</div>
@endsection

