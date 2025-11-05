@extends('layouts.header')
@section('title', 'Đặt hàng thành công')
@section('content')

<div class="bg-white p-5 rounded-4 shadow-sm">
  <h2 class="fw-bold text-success mb-3">Cảm ơn bạn đã đặt hàng!</h2>
  <p class="mb-4">Mã đơn: <strong>#{{ $order->id }}</strong> • Ngày: {{ $order->created_at ?? now() }}</p>

  <h5 class="fw-semibold mb-2">Thông tin giao hàng</h5>
  <ul class="list-unstyled mb-4">
    <li><strong>Tên:</strong> {{ $order->customer_name }}</li>
    <li><strong>Địa chỉ:</strong> {{ $order->customer_address }}</li>
    <li><strong>Trạng thái đơn:</strong> {{ $order->status }}</li>
  </ul>

  <h5 class="fw-semibold mb-2">Thanh toán & vận chuyển</h5>
  <ul class="list-unstyled mb-4">
    @php
      $payMethodMap = ['cod'=>'Thanh toán khi nhận hàng','online'=>'Trực tuyến'];
      $payStatusMap = ['pending'=>'Chờ xử lý','paid'=>'Đã thanh toán','failed'=>'Thất bại'];
      $shipCarrierMap = ['local'=>'Nội bộ'];
      $shipStatusMap = ['pending'=>'Chờ giao','shipping'=>'Đang giao','delivered'=>'Đã nhận hàng','cancelled'=>'Đã hủy'];
    @endphp
    <li><strong>Thanh toán:</strong> {{ $payMethodMap[$payment->method ?? 'cod'] ?? ($payment->method ?? 'cod') }} — {{ $payStatusMap[$payment->status ?? 'pending'] ?? ($payment->status ?? 'pending') }}</li>
    <li><strong>Vận chuyển:</strong> {{ $shipCarrierMap[$shipment->carrier ?? 'local'] ?? ($shipment->carrier ?? 'local') }} — {{ $shipStatusMap[$shipment->status ?? 'pending'] ?? ($shipment->status ?? 'pending') }} @if(($shipment->tracking_code ?? null)) (Mã: {{ $shipment->tracking_code }}) @endif</li>
  </ul>

  <h5 class="fw-semibold mb-2">Sản phẩm trong đơn</h5>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Sản phẩm</th>
          <th class="text-center">SL</th>
          <th class="text-end">Đơn giá</th>
          <th class="text-end">Thành tiền</th>
          <th class="text-center">Đánh giá</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/products/'.$it->product_image) }}" width="48" class="me-2 rounded" alt="{{ $it->product_name }}">
                <div>{{ $it->product_name }}</div>
              </div>
            </td>
            <td class="text-center">{{ $it->quantity }}</td>
            <td class="text-end">{{ number_format($it->price, 0, ',', '.') }} đ</td>
            <td class="text-end">{{ number_format($it->price * $it->quantity, 0, ',', '.') }} đ</td>
            <td class="text-center">
              @php
                $statusCode = \App\Models\Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status);
                $hasReviewed = \App\Models\Review::where('product_id',$it->product_id)->where('user_id', optional(session('admin'))->id)->exists();
              @endphp
              @if($statusCode==='completed' && !$hasReviewed && session('admin'))
                <form method="POST" action="{{ route('product.review.add', $it->product_id) }}" class="d-inline-flex flex-column flex-sm-row align-items-center gap-2 review-inline-form">
                  @csrf
                  <input type="hidden" name="rating" value="5">
                  <div class="star-rate" aria-label="Chọn số sao" title="Chọn số sao">
                    @for($i=1;$i<=5;$i++)
                      <i class="bi bi-star" data-value="{{ $i }}" role="button" tabindex="0" aria-label="{{ $i }} sao"></i>
                    @endfor
                  </div>
                  <textarea name="content" class="form-control form-control-sm" placeholder="Cảm nhận của bạn..." required rows="2" style="min-width:240px"></textarea>
                  <button class="btn btn-sm btn-ocean">Gửi</button>
                </form>
              @elseif($hasReviewed)
                <span class="badge bg-success-subtle text-success border">Đã đánh giá</span>
              @else
                <span class="text-muted small">Chờ nhận hàng</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Tổng cộng</th>
          <th class="text-end text-primary">{{ number_format($order->total, 0, ',', '.') }} đ</th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    @php $statusCode = \App\Models\Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status); @endphp
    @if($statusCode==='shipping')
      <form method="POST" action="{{ route('orders.received',$order->id) }}" onsubmit="return confirm('Xác nhận đã nhận hàng?')">
        @csrf
        <button class="btn btn-success">Tôi đã nhận được hàng</button>
      </form>
    @endif
    <a href="{{ route('home') }}" class="btn btn-ocean">Về trang chủ</a>
  </div>
</div>

<script>
  // Simple star rating handler for inline review forms
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.review-inline-form').forEach(function(form){
      var input = form.querySelector('input[name="rating"]');
      var stars = form.querySelectorAll('.star-rate .bi');
      var set = function(v){
        stars.forEach(function(s,i){ s.classList.toggle('bi-star-fill', i < v); s.classList.toggle('text-warning', i < v); s.classList.toggle('bi-star', i >= v); });
        input.value = v;
      };
      set(parseInt(input.value||'5',10));
      stars.forEach(function(s){
        s.addEventListener('click', function(){ set(parseInt(this.getAttribute('data-value'),10)); });
        s.addEventListener('keydown', function(e){ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); set(parseInt(this.getAttribute('data-value'),10)); }});
      });
    });
  });
</script>

@include('layouts.footer')
@endsection

