<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop')</title>
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      Aqua<span>Shop</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="menu">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Giới thiệu</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cart') }}">Giỏ hàng</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
  @yield('content')
