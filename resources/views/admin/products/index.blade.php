@extends('layouts.admin')
@section('title','Quản lý sản phẩm')
@section('content')

<div class="admin-headerbar">
  <h3>Sản phẩm</h3>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.products.create') }}" class="btn btn-ocean"><i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm</a>
  </div>
</div>

<form class="row g-2 mb-3" method="GET">
  <div class="col-lg-4"><input type="text" name="q" value="{{ $q }}" class="form-control search-input" placeholder="Tìm theo tên sản phẩm..."></div>
  <div class="col-lg-3">
    <select class="form-select search-input" name="category">
      <option value="">Danh mục</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @if($category==$c->id) selected @endif>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-3">
    <select class="form-select search-input" name="brand">
      <option value="">Thương hiệu</option>
      @foreach($brands as $b)
        <option value="{{ $b->id }}" @if($brand==$b->id) selected @endif>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-2">
    <select class="form-select search-input" name="sort">
      <option value="">Sắp xếp</option>
      <option value="price_asc" @if($sort==='price_asc') selected @endif>Giá tăng</option>
      <option value="price_desc" @if($sort==='price_desc') selected @endif>Giá giảm</option>
      <option value="qty_asc" @if($sort==='qty_asc') selected @endif>Tồn ít→nhiều</option>
      <option value="qty_desc" @if($sort==='qty_desc') selected @endif>Tồn nhiều→ít</option>
      <option value="created_desc" @if($sort==='created_desc') selected @endif>Mới nhất</option>
    </select>
  </div>
  <div class="col-12"><button class="btn btn-outline-ocean">Lọc</button> <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none">Xóa lọc</a></div>
</form>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table align-middle">
    <thead>
      <tr>
        <th>#</th><th>Tên</th><th class="text-end">Giá</th><th class="text-center">SL</th><th>Ảnh</th><th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->name }}</td>
          <td class="text-end">{{ number_format($p->price,0,',','.') }} đ</td>
          <td class="text-center">
            @if($p->quantity == 0)
              <span class="badge badge-danger-soft">Hết hàng</span>
            @elseif($p->quantity <= 5)
              <span class="badge badge-danger-soft">Sắp hết ({{ $p->quantity }})</span>
            @else
              <span class="badge badge-soft">{{ $p->quantity }}</span>
            @endif
          </td>
          <td><img src="{{ asset('assets/img/products/'.$p->image) }}" width="48"></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.edit',$p->id) }}">Sửa</a>
            <form method="POST" action="{{ route('admin.products.destroy',$p->id) }}" class="d-inline" onsubmit="return confirm('Xóa sản phẩm?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $products->links() }}

@endsection
