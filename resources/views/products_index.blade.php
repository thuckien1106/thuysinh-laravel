@extends('layouts.header')
@section('title', 'Sản phẩm')
@section('breadcrumb')
  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Sản phẩm']
  ]])
@endsection
@section('content')

<h2 class="fw-bold mb-3">Tất cả sản phẩm</h2>

<form class="row g-2 mb-3" method="GET" action="{{ route('products.index') }}">
  <div class="col-lg-3 col-sm-6"><input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="Tìm tên sản phẩm..."></div>
  <div class="col-lg-2 col-sm-6">
    <select class="form-select" name="category">
      <option value="">Danh mục</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @if($category==$c->id) selected @endif>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-2 col-sm-6">
    <select class="form-select" name="brand">
      <option value="">Thương hiệu</option>
      @foreach($brands as $b)
        <option value="{{ $b->id }}" @if($brand==$b->id) selected @endif>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-2 col-sm-6"><input class="form-control" type="number" step="1000" name="min_price" value="{{ $min }}" placeholder="Giá từ"></div>
  <div class="col-lg-2 col-sm-6"><input class="form-control" type="number" step="1000" name="max_price" value="{{ $max }}" placeholder="đến"></div>
  <div class="col-lg-1 col-sm-6 text-end"><button class="btn btn-ocean w-100">Lọc</button></div>
  <div class="col-12 text-end"><a class="small text-muted" href="{{ route('products.index') }}">Xóa lọc</a></div>
</form>

<div class="row g-4">
  @forelse($products as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100">
      @php $img = 'assets/img/products/'.$p->image; @endphp
      <a href="{{ route('product.show', $p->id) }}">
        <img src="{{ file_exists(public_path($img)) ? asset($img) : asset('assets/img/logo.png') }}" class="card-img-top" alt="{{ $p->name }}">
      </a>
      <div class="card-body position-relative">
        <div class="fw-semibold">{{ $p->name }}</div>
        <div class="text-primary">{{ number_format($p->price, 0, ',', '.') }} đ</div>
        <a href="{{ route('product.show', $p->id) }}" class="stretched-link" aria-label="Xem {{ $p->name }}"></a>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center text-muted">Không tìm thấy sản phẩm.</div>
  @endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>

@include('layouts.footer')
@endsection
