<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop')</title>

  <!-- CSS -->
  <link href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800&family=Poppins:wght@400;600;700&display=swap&subset=vietnamese" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- ================= NAVBAR PREMIUM ================= -->
  <style>
    /* NAVBAR */
    .navbar {
      background: rgba(0, 123, 255, 0.85) !important;
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255,255,255,0.15);
    }

    /* LOGO */
    .navbar-brand {
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 9px rgba(255,255,255,0.4);
    }

    /* LINK */
    .nav-link {
      font-weight: 600 !important;
      padding: 8px 15px !important;
      transition: .25s ease;
    }
    .nav-link:hover {
      color: #ffe082 !important;
      transform: translateY(-1px);
    }
    .nav-link.active,
    .nav-link:focus {
      color: #ffeb3b !important;
    }

    /* SALE link */
    .sale-hot {
      color: #e11d48 !important;        /* đỏ hồng đậm – sắc nét */
      font-weight: 800;
      letter-spacing: 0.3px;
      text-shadow: none;
    }

    .sale-hot .bi {
      color: #ffc107 !important;        /* icon fire vàng đậm */
    }

    .sale-hot:hover {
      color: #be123c !important;        /* đỏ đậm hơn khi hover */
      text-shadow: none;
    }


    /* SEARCH */
    .nav-search {
      width:260px;
      border-radius:40px;
      border:none;
      padding-left:12px !important;
      box-shadow: inset 0 0 6px rgba(0,0,0,0.08);
    }
    .input-group-text {
      border-radius:40px 0 0 40px !important;
      background:#ffffffdd !important;
      border:none !important;
    }

    /* DROPDOWN */
    .dropdown-menu {
      border-radius:16px !important;
      padding:14px !important;
      border:none !important;
      box-shadow:0 12px 26px rgba(0,0,0,0.15),
                 0 4px 16px rgba(0,0,0,0.05);
      animation:fadeDown .25s ease forwards;
    }
    @keyframes fadeDown {
      from { opacity:0; transform: translateY(10px); }
      to   { opacity:1; transform: translateY(0); }
    }

    /* MEGA MENU */
    .nav-mega-grid {
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:18px;
    }

    .dropdown-item {
      padding:8px 12px;
      border-radius:8px;
      transition:0.2s;
    }
    .dropdown-item:hover {
      background:#e3f2fd !important;
    }

    /* CART BADGE */
    #navCartBadge {
      background:linear-gradient(135deg,#FFD54F,#FFB300);
      color:#333 !important;
      font-weight:700;
      box-shadow:0 2px 8px rgba(255,193,7,0.45);
      padding:3px 6px;
    }

    /* THEME BUTTON */
    .theme-toggle {
      background:none;
      border:none;
      color:white;
      font-size:20px;
      padding:6px 8px;
      margin-left:6px;
      transition:0.25s;
    }
    .theme-toggle:hover {
      transform:translateY(-2px) scale(1.05);
      color:#ffeb3b;
    }
  </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
  <div class="container">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      <span class="text-white">Aqua</span>
      <span class="text-warning ms-1">Shop</span>
    </a>

    <!-- MOBILE TOGGLER -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse justify-content-end" id="menu">
      <ul class="navbar-nav align-items-center">

        <!-- DESKTOP SEARCH -->
        <li class="nav-item d-none d-lg-block me-2">
          <form action="{{ route('products.index') }}" method="GET" class="d-flex">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input class="form-control nav-search"
                     type="search" name="q" placeholder="Tìm sản phẩm..."
                     value="{{ request('q') }}">
            </div>
          </form>
        </li>

        <!-- HOME -->
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>

        <!-- PRODUCTS DROPDOWN -->
        <li class="nav-item dropdown d-flex align-items-center">

          <a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a>
          <a class="nav-link dropdown-toggle dropdown-toggle-split"
             data-bs-toggle="dropdown"></a>

          <ul class="dropdown-menu p-3">
            <li>
              <div class="nav-mega-grid">

                <!-- Categories -->
                <div>
                  <div class="dropdown-header fw-bold">Danh mục</div>
                  <ul class="list-unstyled mb-0">
                    @foreach(($categories ?? []) as $c)
                      <li>
                        <a class="dropdown-item"
                           href="{{ route('products.index',['category'=>$c->id]) }}">
                          <i class="bi bi-grid-3x3-gap me-2"></i>{{ $c->name }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <!-- Brands -->
                <div>
                  <div class="dropdown-header fw-bold">Thương hiệu</div>
                  <ul class="list-unstyled mb-0">
                    @foreach(($brands ?? []) as $b)
                      <li>
                        <a class="dropdown-item"
                           href="{{ route('products.index',['brand'=>$b->id]) }}">
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
            <li><a class="dropdown-item text-danger" href="{{ route('products.sale') }}">
              <i class="bi bi-percent me-2"></i>Giảm giá
            </a></li>
          </ul>
        </li>

        <!-- SALE -->
        <li class="nav-item">
          <a class="nav-link sale-hot" href="{{ route('products.sale') }}">
            <i class="bi bi-fire me-1"></i>Sale
          </a>
        </li>

        <!-- ABOUT -->
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Giới thiệu</a></li>

        <!-- CART -->
        @php
          $cartCount = array_sum(array_map(fn($i)=>$i['quantity'] ?? 0, session('cart', [])));
        @endphp

        @if(!session('admin') || session('admin')->role === 'user')
        <li class="nav-item position-relative">
          <a class="nav-link" id="navCartLink" href="{{ route('cart') }}">
            <i class="bi bi-cart3 fs-5"></i>

            <span id="navCartBadge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
              style="{{ $cartCount ? '' : 'display:none;' }}">
              {{ $cartCount }}
            </span>
          </a>
        </li>
        @endif

        <!-- THEME TOGGLE -->
        <li class="nav-item">
          <button class="theme-toggle" id="themeToggle"><i class="bi bi-moon-stars"></i></button>
        </li>

        <!-- USER -->
        @if(session('admin'))
          @include('partials.header_user_dropdown')
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
        @endif

      </ul>

      <!-- MOBILE SEARCH -->
      <li class="nav-item d-lg-none w-100 mt-2">
        <form class="d-flex" action="{{ route('products.index') }}" method="GET">
          <div class="input-group w-100">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input class="form-control" type="search" name="q" placeholder="Tìm sản phẩm...">
          </div>
        </form>
      </li>

    </div>
  </div>
</nav>

<!-- ================= MAIN CONTENT WRAPPER ================= -->
<main class="container py-4">
  @include('partials.flash')
  @hasSection('breadcrumb')
    @yield('breadcrumb')
  @endif
  @yield('content')
</main>

<!-- ================= JS ================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* Hover dropdown for desktop */
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

  /* Theme toggle */
  var body = document.body;
  var saved = localStorage.getItem('theme');
  if (saved === 'dark') body.classList.add('dark');

  var btn = document.getElementById('themeToggle');
  var updateIcon = function() {
    btn.innerHTML = body.classList.contains('dark')
      ? '<i class="bi bi-sun"></i>'
      : '<i class="bi bi-moon-stars"></i>';
  };
  updateIcon();

  btn.addEventListener('click', function(){
    body.classList.toggle('dark');
    localStorage.setItem('theme',
      body.classList.contains('dark') ? 'dark' : 'light');
    updateIcon();
  });

  /* Scroll to top */
  var topBtn = document.getElementById('scrollTopBtn');
  window.addEventListener('scroll', function(){
    if (topBtn) topBtn.style.display =
      (window.scrollY > 300 ? 'flex' : 'none');
  });
  if (topBtn) topBtn.addEventListener('click', function(){
    window.scrollTo({ top:0, behavior:'smooth' });
  });

  /* Offcanvas cart */
  var offcanvasEl = document.getElementById('cartOffcanvas');
  if (!offcanvasEl) {
    var oc = document.createElement('div');
    oc.className = 'offcanvas offcanvas-end';
    oc.id = 'cartOffcanvas';
    oc.tabIndex = -1;
    oc.innerHTML = '<div id="miniCartBody" class="h-100 d-flex flex-column"></div>';
    document.body.appendChild(oc);
  }

  /* Cart badge updater */
  window.updateCartBadge = function(n){
    var badge = document.getElementById('navCartBadge');
    if (!badge) return;
    badge.textContent = n;
    badge.style.display = n > 0 ? 'inline-block' : 'none';
  };
});
</script>

<!-- ================= SEARCH SUGGEST JS ================= -->
<script>
(function(){
  function ready(fn){
    if(document.readyState==='loading'){
      document.addEventListener('DOMContentLoaded', fn);
    } else fn();
  }

  ready(function(){
    var form = document.querySelector('nav form[role="search"]');
    if(!form) return;

    var group = form.querySelector('.input-group');
    var icon  = group && group.querySelector('.input-group-text');
    var input = form.querySelector('input[name="q"]');

    if(icon){
      icon.style.cursor='pointer';
      icon.onclick = () => form.submit();
    }

    if(!input) return;

    var list = document.getElementById('navSearchSuggest');
    if(!list){
      list = document.createElement('div');
      list.id = 'navSearchSuggest';
      list.className = 'search-suggest';
      form.appendChild(list);
    }

    function hide(){ list.style.display='none'; }
    function show(){ list.style.display='block'; }

    var ctrl, t;
    var origin = window.location.origin || '';

    function render(items){
      if(!items.length){ hide(); return; }
      list.innerHTML =
        items.map(it =>
          `<a href="${origin}/product/${it.id}">
            <i class="bi bi-box-seam me-2 text-primary"></i>${it.name}
           </a>`
        ).join('')
        + `<div class="footer"><a href="${origin}/products?q=${encodeURIComponent(input.value||'')}">Tìm "${input.value}"</a></div>`;
      show();
    }

    function fetchSuggest(q){
      if(ctrl && ctrl.abort) ctrl.abort();
      if(!q){ hide(); return; }

      try{ ctrl = new AbortController(); } catch(_){ ctrl = null; }

      fetch(`${origin}/api/search/products?q=${encodeURIComponent(q)}`,
        { signal: ctrl?.signal })
        .then(r => r.json())
        .then(d => render((d ?? []).slice(0,8)))
        .catch(()=>{});
    }

    input.oninput = () => {
      clearTimeout(t);
      let v = input.value.trim();
      t = setTimeout(() => fetchSuggest(v), 160);
    };

    input.onfocus = () => {
      let v = input.value.trim();
      if(v) fetchSuggest(v);
    };

    document.addEventListener('click', e => {
      if(!form.contains(e.target)) hide();
    });
  });
})();
</script>

<!-- SCROLL TOP BUTTON -->
<button id="scrollTopBtn"
        style="position:fixed; right:16px; bottom:18px; display:none;
        align-items:center; justify-content:center;
        width:44px; height:44px; border-radius:50%;
        border:none; background:linear-gradient(90deg, var(--blue-dark), var(--blue-main));
        color:#fff; box-shadow:0 8px 20px rgba(0,0,0,.18); z-index:1050;">
  <i class="bi bi-arrow-up"></i>
</button>

</body>
</html>
