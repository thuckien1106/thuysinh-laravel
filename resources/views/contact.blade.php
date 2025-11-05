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
    <div class="col-md-6 mt-4 mt-md-0">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Thông tin sinh viên thực hiện</h5>
          <ul class="list-unstyled mb-3">
            <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Lê Nguyễn Tuấn Đạt</li>
            <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Hồ Kiến Thức</li>
            <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Trần Hữu Thịnh</li>
          </ul>
          <div class="mb-2"><i class="bi bi-telephone me-2 text-success"></i><strong>Điện thoại:</strong> 0332643954</div>
          <div><i class="bi bi-envelope me-2 text-danger"></i><strong>Email:</strong> <a href="mailto:datle5721@gmail.com">datle5721@gmail.com</a></div>
        </div>
      </div>
      @php 
        $img = 'assets/img/contact_us.webp';
        $fallback = 'assets/img/hero_fish.jpg';
        $src = file_exists(public_path($img)) ? asset($img).'?v='.filemtime(public_path($img)) : asset($fallback);
      @endphp
      <div class="text-center">
        <img src="{{ $src }}" alt="Liên hệ AquaShop" class="img-fluid rounded-3 shadow">
      </div>
    </div>
  </div>
</section>

@include('layouts.footer')
@endsection

