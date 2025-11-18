@extends('layouts.header')
@section('title', 'Đơn hàng của tôi')
@section('content')

<style>
/* ====================== PREMIUM ORDER PAGE ====================== */

/* Tabs */
.nav-tabs .nav-link {
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 10px 10px 0 0;
  color: #0d47a1;
  transition: .25s ease;
}
.nav-tabs .nav-link:hover {
  background: #e3f2fd;
  color: #0b4cb3;
}
.nav-tabs .nav-link.active {
  background: #0d6efd;
  color: white !important;
  border-color: #0d6efd #0d6efd transparent;
  box-shadow: 0 4px 12px rgba(13,110,253,0.25);
}

/* Card container */
.order-wrapper {
  background: #ffffff;
  padding: 28px;
  border-radius: 22px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.07);
}

/* Table */
.table {
  border-radius: 12px;
  overflow: hidden;
}
.table thead {
  background: #f1f5f9;
}
.table tbody tr:hover {
  background: #f8fbff;
}

/* Badges for statuses */
.badge-status {
  padding: 6px 12px;
  font-weight: 600;
  border-radius: 20px;
  font-size: 0.85rem;
}
.badge-processing { background: #ffe08a; color: #7a5600; }
.badge-shipping   { background: #bbdefb; color: #0d47a1; }
.badge-completed  { background: #c8e6c9; color: #256029; }
.badge-cancelled  { background: #ffcdd2; color: #b71c1c; }

/* Payment / shipping statuses */
.badge-small {
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}
.badge-pay-pending  { background:#fff3cd; color:#856404; }
.badge-pay-paid     { background:#d1e7dd; color:#0f5132; }
.badge-pay-failed   { background:#f8d7da; color:#842029; }
.badge-ship-wait    { background:#e3f2fd; color:#0d47a1; }
.badge-ship-run     { background:#bbdefb; color:#0b4cb3; }
.badge-ship-done    { background:#c8e6c9; color:#256029; }
.badge-ship-cancel  { background:#ffcdd2; color:#b71c1c; }

/* Buttons */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5,#00a08a);
  background-size: 200% 200%;
  border: none;
  color: white;
  font-weight: 700;
  padding: 6px 14px;
  border-radius: 10px;
  transition: .3s;
}
.btn-ocean:hover {
  background-position: 100% 0;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0,150,136,0.35);
}

/* Fade-in animation */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: .6s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}
</style>

<div class="order-wrapper fade-in">
  <h3 class="fw-bold mb-3">Đơn hàng của tôi</h3>

  @php $tab = \App\Models\Order::normalizeStatus($statusParam ?? null) ?: 'all'; @endphp

  <!-- NAV TABS -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link {{ $tab==='all' ? 'active' : '' }}" href="{{ route('orders.mine') }}">Tất cả</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='processing' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'processing']) }}">Đang xử lý</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='shipping' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'shipping']) }}">Đang giao</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='completed' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'completed']) }}">Đã nhận hàng</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='cancelled' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'cancelled']) }}">Đã hủy</a></li>
  </ul>

  <!-- TABLE -->
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr class="text-secondary">
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

            $statusCode = \App\Models\Order::normalizeStatus($o->getRawOriginal('status') ?? $o->status);

            $pay = $p->status ?? 'pending';
            $ship = $s->status ?? 'pending';

            $mapPayBadge = [
              'pending' => 'badge-pay-pending',
              'paid' => 'badge-pay-paid',
              'failed' => 'badge-pay-failed'
            ];
            $mapShipBadge = [
              'pending' => 'badge-ship-wait',
              'shipping' => 'badge-ship-run',
              'delivered' => 'badge-ship-done',
              'cancelled' => 'badge-ship-cancel'
            ];

            $mapOrderBadge = [
              'processing' => 'badge-processing',
              'shipping' => 'badge-shipping',
              'completed' => 'badge-completed',
              'cancelled' => 'badge-cancelled'
            ];
          @endphp

          <tr>
            <td>#{{ $o->id }}</td>
            <td>{{ $o->created_at }}</td>
            <td class="text-end">{{ number_format($o->total) }} đ</td>

            <td>
              <span class="badge-status {{ $mapOrderBadge[$statusCode] ?? 'badge-processing' }}">
                {{ ucfirst($o->status) }}
              </span>
            </td>

            <td>
              <span class="badge-small {{ $mapPayBadge[$pay] ?? 'badge-pay-pending' }}">
                {{ $pay === 'pending' ? 'Chờ xử lý' : ($pay === 'paid' ? 'Đã thanh toán' : 'Thất bại') }}
              </span>
            </td>

            <td>
              <span class="badge-small {{ $mapShipBadge[$ship] ?? 'badge-ship-wait' }}">
                {{
                  $ship === 'pending' ? 'Chờ giao' :
                  ($ship === 'shipping' ? 'Đang giao' :
                  ($ship === 'delivered' ? 'Đã nhận hàng' : 'Đã hủy'))
                }}
              </span>
            </td>

            <td class="text-end">

              <a href="{{ route('order.thankyou',$o->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>

              @if($statusCode === 'processing')
                <form method="POST" action="{{ route('orders.cancel',$o->id) }}" class="d-inline" onsubmit="return confirm('Hủy đơn #{{ $o->id }}?')">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger">Hủy</button>
                </form>
              @endif

              @if($statusCode === 'shipping')
                <form method="POST" action="{{ route('orders.received',$o->id) }}" class="d-inline" onsubmit="return confirm('Xác nhận đã nhận đơn này?')">
                  @csrf
                  <button class="btn btn-sm btn-ocean">Đã nhận hàng</button>
                </form>
              @endif

            </td>
          </tr>

        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-3">Bạn chưa có đơn hàng nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@include('layouts.footer')

<script>
document.addEventListener("DOMContentLoaded", () => {
  const el = document.querySelector(".fade-in");
  setTimeout(() => el.classList.add("visible"), 100);
});
</script>

@endsection
