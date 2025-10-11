@extends('layouts.header')
@section('title', 'Bảng điều khiển - Admin')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm">
  <h2 class="fw-bold text-primary mb-3">Bảng điều khiển quản trị</h2>
  <div class="row text-center g-3">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-4">
        <h4 class="text-primary mb-0">12</h4>
        <p>Sản phẩm</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-4">
        <h4 class="text-success mb-0">45</h4>
        <p>Đơn hàng</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-4">
        <h4 class="text-warning mb-0">8</h4>
        <p>Khách hàng</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm p-4">
        <h4 class="text-danger mb-0">2</h4>
        <p>Liên hệ</p>
      </div>
    </div>
  </div>
</section>

@include('layouts.footer')
@endsection
