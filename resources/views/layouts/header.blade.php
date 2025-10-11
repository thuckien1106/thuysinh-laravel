<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop')</title>
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('home') }}">
      <span class="text-white">Aqua</span><span class="text-warning">Shop</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menu">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Giới thiệu</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cart') }}">Giỏ hàng</a></li>

        {{-- Kiểm tra người đăng nhập --}}
        @if(session('admin'))
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
              👋 Xin chào, <strong>{{ session('admin')->username }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              @if(session('admin')->role === 'admin')
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
              @endif
              <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Đăng xuất</a></li>
            </ul>
          </li>
        @else
          {{-- Nếu route login chưa được định nghĩa thì fallback qua login.form --}}
          @php
              $loginRoute = Route::has('login') ? 'login' : (Route::has('login.form') ? 'login.form' : null);
          @endphp

          @if($loginRoute)
            <li class="nav-item">
              <a class="nav-link" href="{{ route($loginRoute) }}">Đăng nhập</a>
            </li>
          @else
            <li class="nav-item">
              <span class="nav-link text-muted">Đăng nhập (route chưa có)</span>
            </li>
          @endif
        @endif
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
