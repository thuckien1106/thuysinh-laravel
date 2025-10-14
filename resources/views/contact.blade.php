@extends('layouts.header')
@section('title', 'Liên hệ - AquaShop')
@section('content')

<section class="p-5 bg-white rounded-4 shadow-sm">
  <h2 class="fw-bold text-primary mb-4 text-center">Liên hệ với chúng tôi</h2>
  <div class="row">
    <div class="col-md-6">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif
      <form method="POST" action="{{ route('contact.submit') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Họ và tên</label>
          <input type="text" name="name" class="form-control form-control-lg" placeholder="Nguyễn Văn A" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control form-control-lg" placeholder="example@email.com">
        </div>
        <div class="mb-3">
          <label class="form-label">Nội dung</label>
          <textarea rows="4" name="message" class="form-control form-control-lg" placeholder="Nhập tin nhắn của bạn..." required></textarea>
        </div>
        <button class="btn btn-ocean w-100">Gửi tin nhắn</button>
      </form>
    </div>
    <div class="col-md-6 text-center mt-4 mt-md-0">
      <img src="{{ asset('assets/img/contact_us.webp') }}" alt="Liên hệ AquaShop" class="img-fluid rounded-3 shadow">
    </div>
  </div>
</section>

@include('layouts.footer')
@endsection

