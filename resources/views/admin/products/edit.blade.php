@extends('layouts.admin')
@section('title','Sửa sản phẩm')
@section('content')

<h3 class="fw-bold mb-3">Sửa sản phẩm #{{ $product->id }}</h3>

@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="row g-3" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div class="col-md-6">
    <label class="form-label">Tên</label>
    <input class="form-control" name="name" value="{{ $product->name }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Giá</label>
    <input type="number" step="0.01" max="99999999.99" class="form-control" name="price" value="{{ $product->price }}" placeholder="<= 99,999,999.99" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Số lượng</label>
    <input type="number" class="form-control" name="quantity" value="{{ $product->quantity }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Danh mục</label>
    <select class="form-select" name="category_id">
      <option value="">-- Chọn --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @if($product->category_id==$c->id) selected @endif>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Thương hiệu</label>
    <select class="form-select" name="brand_id">
      <option value="">-- Chọn --</option>
      @foreach($brands as $b)
        <option value="{{ $b->id }}" @if($product->brand_id==$b->id) selected @endif>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-12">
    <label class="form-label">Ảnh (tên file)</label>
    <input class="form-control mb-2" name="image" value="{{ $product->image }}">
    <div class="form-text">Hoặc tải ảnh mới:</div>
    <input type="file" class="form-control" name="image_file" accept="image/*">
    <div class="mt-2">
      <img src="{{ asset('assets/img/products/'.$product->image) }}" width="80" alt="preview">
    </div>
  </div>
  <div class="col-12">
    <label class="form-label">Mô tả</label>
    <textarea class="form-control" name="description" rows="5">{{ $product->description }}</textarea>
  </div>
  <div class="col-12">
    <label class="form-label">Mô tả ngắn (SEO)</label>
    <input class="form-control" name="short_description" value="{{ $product->short_description }}" placeholder="Tóm tắt 1-2 câu (<=255 ký tự)">
  </div>
  <div class="col-12">
    <label class="form-label">Chi tiết dài</label>
    <textarea class="form-control" name="long_description" rows="7">{{ $product->long_description }}</textarea>
  </div>
  <div class="col-md-6">
    <label class="form-label">Thông số kỹ thuật</label>
    <textarea class="form-control" name="specs" rows="5">{{ $product->specs }}</textarea>
  </div>
  <div class="col-md-6">
    <label class="form-label">Hướng dẫn chăm sóc</label>
    <textarea class="form-control" name="care_guide" rows="5">{{ $product->care_guide }}</textarea>
  </div>
  <div class="col-12 text-end">
    <button class="btn btn-ocean">Cập nhật</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Hủy</a>
  </div>
</form>

@endsection
