@extends('layouts.admin')
@section('title','Bảng điều khiển')
@section('content')

<div class="admin-headerbar">
  <h3>Bảng điều khiển</h3>
  <div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-ocean"><i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm</a>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-center">
        <span class="label">Sản phẩm</span>
        <i class="bi bi-box-seam icon"></i>
      </div>
      <div class="value mt-1">{{ number_format($stats['products']) }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-center">
        <span class="label">Đơn hàng</span>
        <i class="bi bi-receipt icon"></i>
      </div>
      <div class="value mt-1">{{ number_format($stats['orders']) }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-center">
        <span class="label">Doanh thu</span>
        <i class="bi bi-currency-dollar icon"></i>
      </div>
      <div class="value mt-1">{{ number_format($stats['revenue'],0,',','.') }} đ</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-center">
        <span class="label">Người dùng</span>
        <i class="bi bi-people icon"></i>
      </div>
      <div class="value mt-1">{{ number_format($stats['users']) }}</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-3"><div class="stat-card"><div class="label">Đơn hàng hôm nay</div><div class="value mt-1">{{ number_format($stats['orders_today']) }}</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="label">Doanh thu hôm nay</div><div class="value mt-1">{{ number_format($stats['revenue_today'],0,',','.') }} đ</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="label">Đang xử lý</div><div class="value mt-1">{{ number_format($stats['processing']) }}</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="label">Hoàn thành</div><div class="value mt-1">{{ number_format($stats['completed']) }}</div></div></div>
</div>

<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Doanh thu 7 ngày gần đây</h5>
  </div>
  <canvas id="rev7" height="90"></canvas>
</div>

<div class="row g-3 mt-3">
  <div class="col-lg-6">
    <div class="card p-3">
      <h5 class="mb-3">Đơn gần đây</h5>
      <div class="table-responsive"><table class="table table-sm align-middle">
        <thead><tr><th>#</th><th>Khách</th><th class="text-end">Tổng</th><th>Trạng thái</th><th>Ngày</th></tr></thead>
        <tbody>
          @foreach($recentOrders as $o)
            <tr>
              <td><a href="{{ route('admin.orders.show', $o->id) }}" class="text-decoration-none">#{{ $o->id }}</a></td>
              <td>{{ $o->customer_name }}</td>
              <td class="text-end">{{ number_format($o->total,0,',','.') }} đ</td>
              <td><span class="badge badge-soft">{{ $o->status }}</span></td>
              <td>{{ $o->created_at }}</td>
            </tr>
          @endforeach
        </tbody>
      </table></div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card p-3">
      <h5 class="mb-3">Top sản phẩm bán chạy</h5>
      <div class="table-responsive"><table class="table table-sm align-middle">
        <thead><tr><th>Sản phẩm</th><th class="text-center">SL</th><th class="text-end">Doanh thu</th></tr></thead>
        <tbody>
          @foreach($topProducts as $tp)
            <tr>
              <td>{{ $tp->name }}</td>
              <td class="text-center">{{ $tp->qty }}</td>
              <td class="text-end">{{ number_format($tp->amount,0,',','.') }} đ</td>
            </tr>
          @endforeach
        </tbody>
      </table></div>
    </div>
  </div>
 </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const labels = @json($chart['labels']);
  const dataVals = @json($chart['data']);
  new Chart(document.getElementById('rev7'),{
    type:'line',
    data:{
      labels,
      datasets:[{label:'Doanh thu (đ)', data:dataVals, borderColor:'#2ca8ff', backgroundColor:'rgba(44,168,255,.15)', tension:.25, fill:true}]
    },
    options:{plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}
  });
</script>

@endsection

