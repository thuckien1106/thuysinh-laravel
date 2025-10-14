<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop Admin')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('assets/css/admin.css') }}?v={{ @filemtime(public_path('assets/css/admin.css')) }}" rel="stylesheet">
</head>

<body class="admin-body">
<header class="admin-topbar d-flex align-items-center justify-content-between px-3">
  <div class="d-flex align-items-center gap-2">
    <i class="bi bi-water fs-4 text-primary"></i>
    <strong>AquaShop Admin</strong>
  </div>
  <div class="d-flex align-items-center gap-3">
    <span class="text-muted small">{{ session('admin')->username ?? 'guest' }}</span>
    <a class="btn btn-sm btn-outline-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-1"></i>Đăng xuất</a>
  </div>
  </header>

<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <nav class="nav flex-column">
      <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Tổng quan</a>
      <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam me-2"></i>Sản phẩm</a>
      <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt me-2"></i>Đơn hàng</a>
      <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Về trang khách</a>
    </nav>
  </aside>

  <main class="admin-content container-fluid py-4">
    @include('partials.flash')
    @yield('content')
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
