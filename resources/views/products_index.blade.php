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
  <div class="col-lg-2 col-sm-6 d-flex align-items-center">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="saleOnly" name="sale" value="1" @if($sale ?? false) checked @endif>
      <label class="form-check-label" for="saleOnly">Đang giảm giá</label>
    </div>
  </div>
  <div class="col-lg-1 col-sm-6 text-end"><button class="btn btn-ocean w-100">Lọc</button></div>
  <div class="col-12 text-end"><a class="small text-muted" href="{{ route('products.index') }}">Xóa lọc</a></div>
</form>

<div class="row g-4">
  @forelse($products as $p)
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100">
      @php $img = 'assets/img/products/'.$p->image; $isTop = in_array($p->id, $topIds ?? []); $percent = optional($p->activeDiscount)->percent; $ver = file_exists(public_path($img)) ? ('?v='.filemtime(public_path($img))) : ''; @endphp
      <div class="position-relative">
        <a href="{{ route('product.show', $p->id) }}">
          <img src="{{ file_exists(public_path($img)) ? asset($img).$ver : asset('assets/img/logo.png') }}" class="card-img-top product-thumb" alt="{{ $p->name }}">
        </a>
        @if($isTop)
          <span class="badge bg-warning text-dark position-absolute" style="top:8px; left:8px;">Top</span>
        @endif
        @if($percent)
          <span class="badge badge-sale position-absolute" style="top:8px; right:8px;">-{{ $percent }}%</span>
        @endif
      </div>
      <div class="card-body position-relative">
        <div class="fw-semibold">{{ $p->name }}</div>
        @php /* price row uses $percent above */ @endphp
        @if($percent)
          <div class="d-flex align-items-baseline gap-2">
            <div class="text-danger fw-bold">{{ number_format($p->final_price, 0, ',', '.') }} đ</div>
            <div class="text-muted small text-decoration-line-through">{{ number_format($p->price, 0, ',', '.') }} đ</div>
            <span class="badge bg-danger-subtle text-danger border">-{{ $percent }}%</span>
          </div>
        @else
          <div class="text-primary">{{ number_format($p->price, 0, ',', '.') }} đ</div>
        @endif
        @php
          $avg = round((float)\App\Models\Review::where('product_id',$p->id)->avg('rating'),1);
          $cnt = (int)\App\Models\Review::where('product_id',$p->id)->count();
          $sold = (int)\Illuminate\Support\Facades\DB::table('order_details as od')
            ->join('orders as o','o.id','=','od.order_id')
            ->where('od.product_id',$p->id)->where('o.status','completed')->sum('od.quantity');
          $rounded = (int)floor($avg + 0.5);
        @endphp
        @if($cnt>0 || $sold>0)
          <div class="d-flex align-items-center gap-2 mt-1">
            @if($cnt>0)
              <span class="small">
                @for($i=1;$i<=5;$i++)
                  <i class="bi {{ $i <= $rounded ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                @endfor
                <span class="text-muted">{{ $avg }} ({{ $cnt }})</span>
              </span>
            @endif
            @if($sold>0)
              <span class="small text-success"><i class="bi bi-bag-check me-1"></i>Đã bán {{ number_format($sold) }}</span>
            @endif
          </div>
        @endif
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
