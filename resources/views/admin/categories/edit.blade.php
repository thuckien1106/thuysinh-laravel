@extends('layouts.admin')
@section('title','Sửa danh mục')
@section('content')
<div class="admin-headerbar">
  <h3>Sửa danh mục #{{ $category->id }}</h3>
  <div><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-ocean">Quay lại</a></div>
</div>

<div class="card p-3">
  <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="row g-3">
    @csrf @method('PUT')
    <div class="col-md-6">
      <label class="form-label">Tên danh mục</label>
      <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
    </div>
    <div class="col-12">
      <button class="btn btn-ocean">Cập nhật</button>
    </div>
  </form>
</div>
@endsection

