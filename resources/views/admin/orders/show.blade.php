@extends('layouts.admin')
@section('title','Chi tiết đơn hàng')
@section('content')

<h3 class="fw-bold mb-3">Đơn hàng #{{ $order->id }}</h3>

{{-- Flash messages are shown via layouts.admin -> partials/flash. Avoid duplicate here. --}}
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="row g-3">
  <div class="col-md-6">
    <div class="border rounded-3 p-3 h-100">
      <h6 class="fw-semibold">Thông tin khách</h6>
      <div><strong>Tên:</strong> {{ $order->customer_name }}</div>
      <div><strong>Địa chỉ:</strong> {{ $order->customer_address }}</div>
      <div><strong>Ngày tạo:</strong> {{ $order->created_at }}</div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="border rounded-3 p-3 h-100">
      <h6 class="fw-semibold">Trạng thái</h6>
      <form class="d-flex" method="POST" action="{{ route('admin.orders.status',$order->id) }}">
        @csrf
        <select class="form-select me-2" name="status">
          @foreach(\App\Models\Order::STATUS_OPTIONS as $code => $label)
            <option value="{{ $code }}" @if($order->getRawOriginal('status')===$code) selected @endif>{{ $label }}</option>
          @endforeach
        </select>
        <button class="btn btn-ocean">Cập nhật</button>
      </form>
      <hr>
      @php
        $payMethodMap = ['cod'=>'Thanh toán khi nhận hàng','online'=>'Trực tuyến'];
        $payStatusMap = ['pending'=>'Chờ xử lý','paid'=>'Đã thanh toán','failed'=>'Thất bại'];
        $shipCarrierMap = ['local'=>'Nội bộ'];
        $shipStatusMap = ['pending'=>'Chờ giao','shipping'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã hủy'];
      @endphp
      <div><strong>Thanh toán:</strong> {{ $payMethodMap[$payment->method ?? 'cod'] ?? ($payment->method ?? 'cod') }} — {{ $payStatusMap[$payment->status ?? 'pending'] ?? ($payment->status ?? 'pending') }}</div>
      <div><strong>Vận chuyển:</strong> {{ $shipCarrierMap[$shipment->carrier ?? 'local'] ?? ($shipment->carrier ?? 'local') }} — {{ $shipStatusMap[$shipment->status ?? 'pending'] ?? ($shipment->status ?? 'pending') }}</div>
    </div>
  </div>
</div>

<div class="table-responsive mt-3">
  <table class="table align-middle">
    <thead><tr><th>Sản phẩm</th><th class="text-center">SL</th><th class="text-end">Giá</th><th class="text-end">Thành tiền</th></tr></thead>
    <tbody>
      @foreach($items as $it)
        <tr>
          <td>#{{ $it->product_id }}</td>
          <td class="text-center">{{ $it->quantity }}</td>
          <td class="text-end">{{ number_format($it->price,0,',','.') }} đ</td>
          <td class="text-end">{{ number_format($it->price * $it->quantity,0,',','.') }} đ</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr><th colspan="3" class="text-end">Tổng</th><th class="text-end text-primary">{{ number_format($order->total,0,',','.') }} đ</th></tr>
    </tfoot>
  </table>
</div>

<div class="text-end">
  <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Quay lại</a>
</div>

@endsection
