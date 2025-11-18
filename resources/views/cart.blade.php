@extends('layouts.header')
@section('title', 'Giỏ hàng')
@section('content')

<style>
/* =================== EMPTY STATE =================== */
.empty-state {
  text-align: center;
  padding: 40px 10px;
}
.empty-state .icon {
  font-size: 60px;
  color: #00a89d;
}
.empty-state p {
  color: #6c757d;
  font-size: 16px;
}

/* =================== TABLE STYLE =================== */
.cart-table {
  border-radius: 18px;
  overflow: hidden;
  background: #ffffff;
  box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}
.cart-table thead {
  background: #f1f5f9;
}
.cart-table tbody tr:hover {
  background: #f8fbff;
}

/* =================== AUTO NUMBER INPUT =================== */
.qty-input {
  width: 70px;
  text-align: center;
  border-radius: 10px;
}

/* =================== BUTTON OCEAN =================== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00a08a);
  background-size: 200% 200%;
  border: none;
  color: white !important;
  font-weight: 700;
  padding: 10px 20px;
  border-radius: 12px;
  transition: .3s ease;
}
.btn-ocean:hover {
  background-position: 100% 0;
  transform: translateY(-2px);
  box-shadow: 0 8px 18px rgba(0,150,136,0.35);
}

/* BUTTON OUTLINE */
.btn-outline-primary {
  border-radius: 10px;
}

/* DELETE BUTTON */
.btn-remove {
  color: #e63946;
  font-weight: 600;
}
.btn-remove:hover {
  text-decoration: underline;
}

/* =================== PRICE BOX =================== */
.total-box {
  border-radius: 18px;
  background: #ffffff;
  padding: 20px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.07);
  animation: fadeInUp .6s ease;
}

/* =================== ANIMATION =================== */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(15px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<div class="fade-in">
  <h2 class="fw-bold text-primary mb-4">🛒 Giỏ hàng của bạn</h2>

  @if(empty($cart))
    <div class="card shadow-sm rounded-4 border-0 fade-in">
      <div class="card-body empty-state">
        <div class="icon mb-2"><i class="bi bi-bag-dash"></i></div>
        <p class="mb-3">Hiện tại bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="{{ route('home') }}" class="btn btn-ocean">Tiếp tục mua sắm</a>
      </div>
    </div>

  @else

    <!-- COUPON -->
    <div class="row mb-3 fade-in">
      <div class="col-md-6"></div>
      <div class="col-md-6">
        <form class="d-flex justify-content-end" method="POST" action="{{ route('cart.coupon') }}">
          @csrf
          <input type="text" name="code" class="form-control me-2" placeholder="🎟️ Mã khuyến mãi" style="max-width:220px">
          <button class="btn btn-outline-primary px-3">Áp dụng</button>
        </form>

        @error('coupon')
          <div class="text-danger small text-end mt-1">{{ $message }}</div>
        @enderror

        @if(!empty($coupon))
          <div class="text-success small text-end mt-1">
            Đã áp dụng mã: <strong>{{ $coupon['code'] }}</strong>
          </div>
        @endif
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive fade-in">
      <table class="table align-middle cart-table">
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
                <img src="{{ file_exists(public_path($img)) ? asset($img) : asset('assets/img/logo.png') }}"
                     width="60"
                     class="me-3 rounded shadow-sm"
                     alt="{{ $item['name'] }}">
                <strong>{{ $item['name'] }}</strong>
              </div>
            </td>

            <td class="text-end">{{ number_format($item['price']) }} đ</td>

            <td class="text-center">
              <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <input type="number" name="quantity"
                       value="{{ $item['quantity'] }}"
                       min="1"
                       class="form-control form-control-sm qty-input d-inline-block">

                {{-- Hidden update button (auto-submit) --}}
                <button class="btn btn-sm btn-outline-primary ms-1 d-none">Cập nhật</button>
              </form>
            </td>

            <td class="text-end fw-semibold text-primary">
              {{ number_format($item['price'] * $item['quantity']) }} đ
            </td>

            <td class="text-end">
              <form method="POST" action="{{ route('cart.remove', $item['id']) }}"
                    onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                @csrf @method('DELETE')
                <button class="btn btn-remove btn-sm">Xóa</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <!-- TOTAL BOX -->
    <div class="d-flex justify-content-end mt-3">
      <div class="total-box fade-in" style="min-width:320px">
        <div class="fs-6 mb-1">Tạm tính: <strong>{{ number_format($total) }} đ</strong></div>
        <div class="fs-6 mb-1">Giảm giá: <strong class="text-success">-{{ number_format($discount) }} đ</strong></div>
        <hr>
        <div class="fs-4">
          Tổng thanh toán:
          <strong class="text-primary">{{ number_format($grand_total) }} đ</strong>
        </div>

        @if(session('admin'))
          <a href="{{ route('checkout') }}" class="btn btn-ocean w-100 mt-3">
            👉 Tiến hành thanh toán
          </a>
        @else
          <a href="{{ url('/login') }}" class="btn btn-ocean w-100 mt-3">
            🔐 Đăng nhập để thanh toán
          </a>
        @endif

      </div>
    </div>

  @endif
</div>

@include('layouts.footer')

<script>
/* Auto update quantity */
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('input[name="quantity"]').forEach(function(inp){
    const form = inp.closest('form');
    let timer;

    function submitNow(){ form.submit(); }
    function debounce(){ clearTimeout(timer); timer = setTimeout(submitNow, 350); }

    inp.addEventListener('change', submitNow);
    inp.addEventListener('input', debounce);
    inp.addEventListener('blur', submitNow);
  });

  /* Fade-in effect */
  const els = document.querySelectorAll('.fade-in');
  els.forEach((el,i)=> setTimeout(()=>el.classList.add('visible'), 120*i));
});
</script>

@endsection
