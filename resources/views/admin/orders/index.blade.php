@extends('layouts.admin')
@section('title','Quản lý đơn hàng')
@section('content')

<div class="admin-headerbar">
  <h3>Đơn hàng</h3>
  <form class="row g-2 align-items-end" method="GET">
    <div class="col-lg-3">
    <select class="form-select search-input" name="status">
      <option value="">-- Tất cả trạng thái --</option>
      @foreach(['Đang xử lý','Đang giao','Hoàn thành','Đã hủy'] as $st)
        <option value="{{ $st }}" @if($status==$st) selected @endif>{{ $st }}</option>
      @endforeach
    </select>
    </div>
    <div class="col-lg-3"><input class="form-control search-input" type="text" name="q" value="{{ $q }}" placeholder="Tìm ID/khách"></div>
    <div class="col-lg-2"><input class="form-control search-input" type="date" name="from" value="{{ $from }}" ></div>
    <div class="col-lg-2"><input class="form-control search-input" type="date" name="to" value="{{ $to }}" ></div>
    <div class="col-lg-2">
      <select class="form-select search-input" name="sort">
        <option value="">Mặc định</option>
        <option value="date_asc" @if($sort==='date_asc') selected @endif>Ngày ↑</option>
        <option value="total_desc" @if($sort==='total_desc') selected @endif>Tổng ↓</option>
        <option value="total_asc" @if($sort==='total_asc') selected @endif>Tổng ↑</option>
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-outline-ocean">Lọc</button>
      <a class="btn btn-link text-decoration-none" href="{{ route('admin.orders.index') }}">Xóa lọc</a>
      <a class="btn btn-ocean" href="{{ route('admin.orders.export.csv', request()->all()) }}"><i class="bi bi-download me-1"></i>Xuất CSV</a>
    </div>
  </form>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>#</th><th>Ngày</th><th>Khách</th><th class="text-end">Tổng</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
      @foreach($orders as $o)
        <tr>
          <td>#{{ $o->id }}</td>
          <td>{{ $o->created_at }}</td>
          <td>{{ $o->customer_name }}</td>
          <td class="text-end">{{ number_format($o->total,0,',','.') }} đ</td>
          <td>{{ $o->status }}</td>
          <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show',$o->id) }}">Xem</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $orders->links() }}

@endsection
