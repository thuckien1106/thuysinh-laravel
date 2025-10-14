@extends('layouts.admin')
@section('title','Thêm sản phẩm')
@section('content')

<h3 class="fw-bold mb-3">Thêm sản phẩm</h3>

@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('admin.products.store') }}" class="row g-3" enctype="multipart/form-data">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Tên</label>
    <input class="form-control" name="name" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Giá</label>
    <input type="number" step="0.01" max="99999999.99" class="form-control" name="price" placeholder="<= 99,999,999.99" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Số lượng</label>
    <input type="number" class="form-control" name="quantity" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Danh mục</label>
    <select class="form-select" name="category_id">
      <option value="">-- Chọn --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}">{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Thương hiệu</label>
    <select class="form-select" name="brand_id">
      <option value="">-- Chọn --</option>
      @foreach($brands as $b)
        <option value="{{ $b->id }}">{{ $b->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-12">
    <label class="form-label">Ảnh (tên file trong assets/img/products)</label>
    <input class="form-control mb-2" name="image" placeholder="vd: reu_java.webp">
    <div class="form-text">Hoặc tải ảnh từ máy:</div>
    <input type="file" class="form-control" name="image_file" accept="image/*">
  </div>
  <div class="col-12">
    <label class="form-label">Mô tả</label>
    <textarea class="form-control" name="description" rows="5"></textarea>
  </div>
  <div class="col-12 text-end">
    <button class="btn btn-ocean">Lưu</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Hủy</a>
  </div>
  
</form>

@endsection
