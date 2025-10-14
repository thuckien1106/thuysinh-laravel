@extends('layouts.header')
@section('title', 'Giỏ hàng')
@section('content')

<h2 class="fw-bold text-primary mb-4">Giỏ hàng của bạn</h2>

{{-- Thông báo đã hiển thị qua layouts.header/partials.flash; ẩn ở trang giỏ để tránh trùng/không cần thiết --}}

@if(empty($cart))
  <div class="card shadow-sm rounded-4 border-0">
    <div class="card-body empty-state">
      <div class="icon mb-2"><i class="bi bi-bag-dash"></i></div>
      <p class="mb-3">Hiện tại bạn chưa có sản phẩm nào trong giỏ hàng.</p>
      <a href="{{ route('home') }}" class="btn btn-ocean">Tiếp tục mua sắm</a>
    </div>
  </div>
@else
  <div class="row mb-3">
    <div class="col-md-6"></div>
    <div class="col-md-6">
      <form class="d-flex justify-content-end" method="POST" action="{{ route('cart.coupon') }}">
        @csrf
        <input type="text" name="code" class="form-control me-2" placeholder="Mã khuyến mãi" style="max-width:200px">
        <button class="btn btn-outline-primary">Áp dụng</button>
      </form>
      @error('coupon')
        <div class="text-danger small text-end">{{ $message }}</div>
      @enderror
      @if(!empty($coupon))
        <div class="text-end small text-success">Đã áp dụng: {{ $coupon['code'] }}</div>
      @endif
    </div>
  </div>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th class="text-end">Giá</th>
          <th class="text-center" style="width:120px">Số lượng</th>
          <th class="text-end">Thành tiền</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      @foreach($cart as $item)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              @php $img = 'assets/img/products/'.$item['image']; @endphp
              <img src="{{ file_exists(public_path($img)) ? asset($img) : asset('assets/img/logo.png') }}" width="56" class="me-3 rounded" alt="{{ $item['name'] }}">
              <div>{{ $item['name'] }}</div>
            </div>
          </td>
          <td class="text-end">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
          <td class="text-center">
            <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="d-inline">
              @csrf
              @method('PATCH')
              <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm text-center" style="width:80px; display:inline-block;">
              <button class="btn btn-sm btn-outline-primary ms-1">Cập nhật</button>
            </form>
          </td>
          <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</td>
          <td class="text-end">
            <form method="POST" action="{{ route('cart.remove', $item['id']) }}" onsubmit="return confirm('Xóa sản phẩm này?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-link text-danger">Xóa</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <div class="d-flex flex-column align-items-end mt-3">
    <div class="fs-6">Tạm tính: <strong>{{ number_format($total, 0, ',', '.') }} đ</strong></div>
    <div class="fs-6">Giảm giá: <strong class="text-success">-{{ number_format($discount, 0, ',', '.') }} đ</strong></div>
    <div class="fs-5">Tổng thanh toán: <strong class="text-primary">{{ number_format($grand_total, 0, ',', '.') }} đ</strong></div>
    @if(session('admin'))
      <a href="{{ route('checkout') }}" class="btn btn-ocean mt-2">Thanh toán</a>
    @else
      <a href="{{ url('/login') }}" class="btn btn-ocean mt-2">Đăng nhập để thanh toán</a>
    @endif
  </div>
@endif

@include('layouts.footer')
@endsection

<style>
/* Ẩn nút Cập nhật trong bảng giỏ hàng (auto-submit khi thay đổi số lượng) */
.table .btn.btn-outline-primary.ms-1 { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var qtyInputs = document.querySelectorAll('form input[name="quantity"]');
  qtyInputs.forEach(function(inp){
    var form = inp.closest('form');
    if (!form) return;
    var timer;
    function submitNow(){ try { form.submit(); } catch(e){} }
    function debounced(){ clearTimeout(timer); timer = setTimeout(submitNow, 400); }
    inp.addEventListener('change', submitNow);
    inp.addEventListener('input', debounced);
    inp.addEventListener('blur', submitNow);
  });
});
</script>
