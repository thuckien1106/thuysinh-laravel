@extends('layouts.header')
@section('title', 'Giới thiệu - AquaShop')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm">
  <div class="row align-items-center">
    <div class="col-md-6">
      <h2 class="fw-bold text-primary mb-3">Về AquaShop</h2>
      <p class="text-secondary">
        AquaShop được thành lập với sứ mệnh mang đến những sản phẩm thủy sinh chất lượng, 
        giúp bạn tạo dựng một không gian sống xanh, thư giãn và gần gũi với thiên nhiên.
      </p>
      <p class="text-secondary">
        Chúng tôi cung cấp đa dạng cây thủy sinh, cá cảnh và phụ kiện, kèm dịch vụ tư vấn tận tâm
        để bạn dễ dàng thiết kế hồ cá hoàn hảo.
      </p>
      <a href="{{ route('contact') }}" class="btn btn-ocean mt-3">Liên hệ ngay</a>
    </div>
    <div class="col-md-6 text-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="AquaShop" class="img-fluid rounded-3 shadow">
    </div>
  </div>
</section>

@include('layouts.footer')
@endsection
