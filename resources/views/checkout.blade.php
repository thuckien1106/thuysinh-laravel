@extends('layouts.header')
@section('title', 'Thanh toán')
@section('content')

<section class="bg-white p-5 rounded-4 shadow-sm">
  <h2 class="fw-bold text-primary mb-4">Thanh toán đơn hàng</h2>
  <form>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Họ tên</label>
        <input type="text" class="form-control form-control-lg" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Số điện thoại</label>
        <input type="text" class="form-control form-control-lg" required>
      </div>
      <div class="col-12">
        <label class="form-label">Địa chỉ giao hàng</label>
        <input type="text" class="form-control form-control-lg" required>
      </div>
      <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea class="form-control form-control-lg" rows="3"></textarea>
      </div>
      <div class="col-12 text-end mt-4">
        <button class="btn btn-ocean px-5">Xác nhận đặt hàng</button>
      </div>
    </div>
  </form>
</section>

@include('layouts.footer')
@endsection
