@extends('layouts.admin')
@section('title','Thêm danh mục')
@section('content')
<div class="admin-headerbar">
  <h3>Thêm danh mục</h3>
  <div><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-ocean">Quay lại</a></div>
</div>

<div class="card p-3">
  <form method="POST" action="{{ route('admin.categories.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
      <label class="form-label">Tên danh mục</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-ocean">Lưu</button>
    </div>
  </form>
</div>
@endsection

