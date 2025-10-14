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
      $shipStatusMap = ['pending'=>'Chờ giao','shipping'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã hủy'];
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

  <div class="text-end">
    <a href="{{ route('home') }}" class="btn btn-ocean">Về trang chủ</a>
  </div>
</div>

@include('layouts.footer')
@endsection

