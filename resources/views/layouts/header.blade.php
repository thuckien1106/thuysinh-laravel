<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop')</title>
  <link href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Hide legacy text cart link in navbar; we show icon version instead */
    .navbar a.nav-link[href='{{ route('cart') }}']:not(#navCartLink){ display:none !important; }
    #navCartBadge{ line-height:1; }
    /* Sale nav emphasis */
    /* SALE link (sharp, non-blurry) */
    .sale-hot{
      color:#e11d48 !important; /* strong red */
      font-weight:800;
      text-shadow:none; /* avoid blur */
      background:none;  /* remove gradient text to keep crisp */
      -webkit-text-fill-color:unset;
      animation:none;
    }
    .sale-hot .bi{color:#ffc107 !important; filter:none; text-shadow:none;}
    .sale-hot:hover{ color:#be123c !important; }
    /* Sale badge on product image */
    .badge-sale{ background:linear-gradient(90deg,#ef4444,#f97316); color:#fff; border:none; box-shadow:0 6px 16px rgba(239,68,68,.25); }
    /* Navbar search */
    .nav-search{ width:260px; }
    @media (max-width: 992px){ .nav-search{ width:100%; } }
  </style>
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
        <li class="nav-item d-none d-lg-block me-2">
          <form class="d-flex" action="{{ route('products.index') }}" method="GET" role="search">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input class="form-control nav-search" type="search" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm...">
            </div>
          </form>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
        <li class="nav-item dropdown d-flex align-items-center">
          <a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a>
          <a class="nav-link dropdown-toggle dropdown-toggle-split" href="#" id="navProducts" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Mở danh mục sản phẩm"></a>
          <ul class="dropdown-menu p-3" aria-labelledby="navProducts" data-bs-auto-close="true">
            <li class="px-1 py-1">
              <div class="nav-mega-grid">
                <div>
                  <div class="dropdown-header">Danh mục</div>
                  <ul class="list-unstyled mb-0">
                    @foreach(($categories ?? []) as $c)
                      <li>
                        <a class="dropdown-item" href="{{ route('products.index', ['category'=>$c->id]) }}">
                          <i class="bi bi-grid-3x3-gap me-2"></i>{{ $c->name }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
                <div>
                  <div class="dropdown-header">Thương hiệu</div>
                  <ul class="list-unstyled mb-0">
                    @foreach(($brands ?? []) as $b)
                      <li>
                        <a class="dropdown-item" href="{{ route('products.index', ['brand'=>$b->id]) }}">
                          <i class="bi bi-award me-2"></i>{{ $b->name }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('products.index') }}">Tất cả sản phẩm</a></li>
            <li><a class="dropdown-item text-danger" href="{{ route('products.sale') }}"><i class="bi bi-percent me-2"></i>Đang giảm giá</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link sale-hot" href="{{ route('products.sale') }}"><i class="bi bi-fire me-1"></i>Sale</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Giới thiệu</a></li>
        @if(!session('admin') || (session('admin')->role ?? 'user') === 'user')
          <li class="nav-item"><a class="nav-link" href="{{ route('cart') }}">Giỏ hàng</a></li>
          @php $cartCount = array_sum(array_map(fn($i)=>$i['quantity'] ?? 0, session('cart', []))); @endphp
          <li class="nav-item position-relative">
            <a id="navCartLink" class="nav-link" href="{{ route('cart') }}" aria-label="Giỏ hàng">
              <i class="bi bi-cart3 fs-5"></i>
              <span id="navCartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size:10px; {{ $cartCount ? '' : 'display:none;' }}">{{ $cartCount }}</span>
            </a>
          </li>
        @endif
        <li class="nav-item">
          <button class="theme-toggle" id="themeToggle" title="Đổi giao diện" aria-label="Đổi giao diện"><i class="bi bi-moon-stars"></i></button>
        </li>

        <li class="nav-item d-lg-none w-100 mt-2">
          <form class="d-flex" action="{{ route('products.index') }}" method="GET" role="search">
            <div class="input-group w-100">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm...">
            </div>
          </form>
        </li>

        @if(session('admin'))
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
              Xin chào, <strong>{{ session('admin')->username }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              @if((session('admin')->role ?? 'user') === 'user')
                <li><a class="dropdown-item" href="{{ route('account.profile') }}">Tài khoản của tôi</a></li>
                <li><a class="dropdown-item" href="{{ route('orders.mine') }}">Đơn hàng của tôi</a></li>
              @endif
              @if(session('admin')->role === 'admin')
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
              @endif
              <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Đăng xuất</a></li>
            </ul>
          </li>
        @else
          @php
            $loginRoute = Route::has('login') ? 'login' : (Route::has('login.form') ? 'login.form' : null);
          @endphp
          @if($loginRoute)
            <li class="nav-item"><a class="nav-link" href="{{ route($loginRoute) }}">Đăng nhập</a></li>
          @endif
        @endif
      </ul>
    </div>
  </div>
</nav>

  <main class="container py-4">
    @include('partials.flash')
    @hasSection('breadcrumb')
      @yield('breadcrumb')
    @endif
    @yield('content')
</main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    // Hover-to-open for all dropdowns on desktop (both split and regular toggles)
    document.querySelectorAll('.navbar .dropdown').forEach(function(dd){
      var toggle = dd.querySelector('[data-bs-toggle="dropdown"]');
      if (!toggle) return;
      var inst = bootstrap.Dropdown.getOrCreateInstance(toggle, { autoClose: true });
      var openTimer, closeTimer;
      dd.addEventListener('mouseenter', function(){
        if (window.innerWidth < 992) return;
        clearTimeout(closeTimer);
        openTimer = setTimeout(function(){ inst.show(); }, 120);
      });
      dd.addEventListener('mouseleave', function(){
        if (window.innerWidth < 992) return;
        clearTimeout(openTimer);
        closeTimer = setTimeout(function(){ inst.hide(); }, 150);
      });
    });

    // Only keep theme toggle and scroll-to-top below.
    // Theme toggle (light/dark)
    var body = document.body;
    var saved = localStorage.getItem('theme');
    if (saved === 'dark') { body.classList.add('dark'); }
    var btn = document.getElementById('themeToggle');
    var updateIcon = function(){ btn && (btn.innerHTML = body.classList.contains('dark') ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>'); };
    updateIcon();
    btn && btn.addEventListener('click', function(){ body.classList.toggle('dark'); localStorage.setItem('theme', body.classList.contains('dark') ? 'dark' : 'light'); updateIcon(); });

    // Scroll to top button
    var topBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){ if (topBtn) topBtn.style.display = (window.scrollY > 300 ? 'flex' : 'none'); });
    topBtn && topBtn.addEventListener('click', function(){ window.scrollTo({ top:0, behavior:'smooth' }); });

    // Offcanvas cart element
    var offcanvasEl = document.getElementById('cartOffcanvas');
    if (!offcanvasEl) {
      var oc = document.createElement('div');
      oc.className = 'offcanvas offcanvas-end';
      oc.id = 'cartOffcanvas';
      oc.tabIndex = -1;
      oc.innerHTML = '<div id="miniCartBody" class="h-100 d-flex flex-column"></div>';
      document.body.appendChild(oc);
    }

    // Helper: update cart badge dynamically
    window.updateCartBadge = function(n){
      var badge = document.getElementById('navCartBadge');
      if (!badge) return;
      var cur = parseInt(badge.textContent || '0', 10) || 0;
      var next = (typeof n === 'number') ? n : cur;
      badge.textContent = next;
      badge.style.display = next > 0 ? 'inline-block' : 'none';
    };
  });
  </script>
  <button id="scrollTopBtn" style="position:fixed; right:16px; bottom:18px; display:none; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; border:none; background:linear-gradient(90deg, var(--blue-dark), var(--blue-main)); color:#fff; box-shadow:0 8px 20px rgba(0,0,0,.18); z-index:1050;">
    <i class="bi bi-arrow-up"></i>
  </button>
</body>
</html>
