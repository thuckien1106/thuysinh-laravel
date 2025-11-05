@extends('layouts.header')
@section('title', 'Đơn hàng của tôi')
@section('content')

<div class="bg-white p-4 rounded-4 shadow-sm">
  <h3 class="fw-bold mb-3">Đơn hàng của tôi</h3>
  @php $tab = \App\Models\Order::normalizeStatus($statusParam ?? null) ?: 'all'; @endphp
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link {{ $tab==='all' ? 'active' : '' }}" href="{{ route('orders.mine') }}">Tất cả</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='processing' ? 'active' : '' }}" href="{{ route('orders.mine', ['status'=>'processing']) }}">Đang xử lý</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='shipping' ? 'active' : '' }}" href="{{ route('orders.mine', ['status'=>'shipping']) }}">Đang giao</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='completed' ? 'active' : '' }}" href="{{ route('orders.mine', ['status'=>'completed']) }}">Đã nhận hàng</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='cancelled' ? 'active' : '' }}" href="{{ route('orders.mine', ['status'=>'cancelled']) }}">Đã hủy</a></li>
  </ul>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Ngày</th>
          <th class="text-end">Tổng</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Vận chuyển</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
          @php
            $p = optional($payments->get($o->id))->last();
            $s = optional($shipments->get($o->id))->last();
            $payStatusMap = ['pending'=>'Chờ xử lý','paid'=>'Đã thanh toán','failed'=>'Thất bại'];
            $shipStatusMap = ['pending'=>'Chờ giao','shipping'=>'Đang giao','delivered'=>'Đã nhận hàng','cancelled'=>'Đã hủy'];
            $statusCode = \App\Models\Order::normalizeStatus($o->getRawOriginal('status') ?? $o->status);
          @endphp
          <tr>
            <td>#{{ $o->id }}</td>
            <td>{{ $o->created_at ?? '' }}</td>
            <td class="text-end">{{ number_format($o->total, 0, ',', '.') }} đ</td>
            <td>{{ $o->status }}</td>
            <td>{{ $payStatusMap[$p->status ?? 'pending'] ?? ($p->status ?? 'pending') }}</td>
            <td>{{ $shipStatusMap[$s->status ?? 'pending'] ?? ($s->status ?? 'pending') }}</td>
            <td class="text-end">
              <a href="{{ route('order.thankyou', $o->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
              @if($statusCode==='processing')
                <form method="POST" action="{{ route('orders.cancel',$o->id) }}" class="d-inline" onsubmit="return confirm('Hủy đơn #{{ $o->id }}?')">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger">Hủy</button>
                </form>
              @endif
              @if($statusCode==='shipping')
                <form method="POST" action="{{ route('orders.received',$o->id) }}" class="d-inline" onsubmit="return confirm('Xác nhận đã nhận hàng cho đơn #{{ $o->id }}?')">
                  @csrf
                  <button class="btn btn-sm btn-ocean">Đã nhận hàng</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@include('layouts.footer')
@endsection

