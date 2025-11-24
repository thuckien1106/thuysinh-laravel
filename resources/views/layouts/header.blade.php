<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS -->
  <link href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) }}"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800&family=Poppins:wght@400;600;700&display=swap&subset=vietnamese"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- ================= NAVBAR PREMIUM ================= -->
  <style>
    /* NAVBAR */
    .navbar {
      background: rgba(0, 123, 255, 0.85) !important;
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    /* LOGO */
    .navbar-brand {
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 9px rgba(255, 255, 255, 0.4);
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

    .nav-item.dropdown .dropdown-toggle {
      margin-left: -4px !important;
    }

    /* SALE link */
    .sale-hot {
      color: #e11d48 !important;
      /* đỏ hồng đậm – sắc nét */
      font-weight: 800;
      letter-spacing: 0.3px;
      text-shadow: none;
    }

    .sale-hot .bi {
      color: #ffc107 !important;
      /* icon fire vàng đậm */
    }

    .sale-hot:hover {
      color: #be123c !important;
      /* đỏ đậm hơn khi hover */
      text-shadow: none;
    }


    /* SEARCH */
    .nav-search {
      width: 260px;
      border-radius: 40px;
      border: none;
      padding-left: 12px !important;
      box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.08);
    }

    .input-group-text {
      border-radius: 40px 0 0 40px !important;
      background: #ffffffdd !important;
      border: none !important;
    }

    /* DROPDOWN */
    .dropdown-menu {
      border-radius: 16px !important;
      padding: 14px !important;
      border: none !important;
      box-shadow: 0 12px 26px rgba(0, 0, 0, 0.15),
        0 4px 16px rgba(0, 0, 0, 0.05);
      animation: fadeDown .25s ease forwards;
    }

    @keyframes fadeDown {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* MEGA MENU */
    .nav-mega-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }

    .dropdown-item {
      padding: 8px 12px;
      border-radius: 8px;
      transition: 0.2s;
    }

    .dropdown-item:hover {
      background: #e3f2fd !important;
    }

    /* CART BADGE */
    #navCartBadge {
      background: linear-gradient(135deg, #FFD54F, #FFB300);
      color: #333 !important;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.45);
      padding: 3px 6px;
    }

    /* THEME BUTTON */
    .theme-toggle {
      background: none;
      border: none;
      color: white;
      font-size: 20px;
      padding: 6px 8px;
      margin-left: 6px;
      transition: 0.25s;
    }

    .theme-toggle:hover {
      transform: translateY(-2px) scale(1.05);
      color: #ffeb3b;
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
                <input class="form-control nav-search" type="search" name="q" placeholder="Tìm sản phẩm..."
                  value="{{ request('q') }}">
              </div>
            </form>
          </li>

          <!-- HOME -->
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>

          <!-- PRODUCTS DROPDOWN -->
          <li class="nav-item dropdown d-flex align-items-center">

            <!-- NÚT ĐI TỚI TRANG TẤT CẢ SẢN PHẨM -->
            <a class="nav-link" href="{{ route('products.index') }}">
              Sản phẩm
            </a>

            <!-- NÚT MỞ DROPDOWN (CHỈ MŨI TÊN) -->
            <a class="nav-link dropdown-toggle px-0 ms-n2" href="#" data-bs-toggle="dropdown"></a>
            <ul class="dropdown-menu p-3">
              <li>
                <div class="nav-mega-grid">

                  <!-- Categories -->
                  <div>
                    <div class="dropdown-header fw-bold">Danh mục</div>
                    <ul class="list-unstyled mb-0">
                      @foreach(($categories ?? []) as $c)
                      <li>
                        <a class="dropdown-item" href="{{ route('products.index', ['category' => $c->id]) }}">
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
                        <a class="dropdown-item" href="{{ route('products.index', ['brand' => $b->id]) }}">
                          <i class="bi bi-award me-2"></i>{{ $b->name }}
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>

                </div>
              </li>

              <li>
                <hr class="dropdown-divider">
              </li>

              <li><a class="dropdown-item" href="{{ route('products.index') }}">Tất cả sản phẩm</a></li>
              <li>
                <a class="dropdown-item text-danger" href="{{ route('products.sale') }}">
                  <i class="bi bi-percent me-2"></i>Giảm giá
                </a>
              </li>
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
          $cartCount = array_sum(array_map(fn($i) => $i['quantity'] ?? 0, session('cart', [])));
          @endphp

          @if(!session('admin') || session('admin')->role === 'user')
          <li class="nav-item position-relative">
            <a class="nav-link" id="navCartLink" href="{{ route('cart') }}">
              <i class="bi bi-cart3 fs-5"></i>

              <span id="navCartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
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
    document.addEventListener('DOMContentLoaded', function() {

      /* Hover dropdown for desktop */
      document.querySelectorAll('.navbar .dropdown').forEach(function(dd) {
        var toggle = dd.querySelector('[data-bs-toggle="dropdown"]');
        if (!toggle) return;

        var inst = bootstrap.Dropdown.getOrCreateInstance(toggle, {
          autoClose: true
        });
        var openTimer, closeTimer;

        dd.addEventListener('mouseenter', function() {
          if (window.innerWidth < 992) return;
          clearTimeout(closeTimer);
          openTimer = setTimeout(function() {
            inst.show();
          }, 120);
        });

        dd.addEventListener('mouseleave', function() {
          if (window.innerWidth < 992) return;
          clearTimeout(openTimer);
          closeTimer = setTimeout(function() {
            inst.hide();
          }, 150);
        });
      });

      /* Theme toggle */
      var body = document.body;
      var saved = localStorage.getItem('theme');
      if (saved === 'dark') body.classList.add('dark');

      var btn = document.getElementById('themeToggle');
      var updateIcon = function() {
        btn.innerHTML = body.classList.contains('dark') ?
          '<i class="bi bi-sun"></i>' :
          '<i class="bi bi-moon-stars"></i>';
      };
      updateIcon();

      btn.addEventListener('click', function() {
        body.classList.toggle('dark');
        localStorage.setItem('theme',
          body.classList.contains('dark') ? 'dark' : 'light');
        updateIcon();
      });

      /* Scroll to top */
      var topBtn = document.getElementById('scrollTopBtn');
      window.addEventListener('scroll', function() {
        if (topBtn) topBtn.style.display =
          (window.scrollY > 300 ? 'flex' : 'none');
      });
      if (topBtn) topBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
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
      window.updateCartBadge = function(n) {
        var badge = document.getElementById('navCartBadge');
        if (!badge) return;
        badge.textContent = n;
        badge.style.display = n > 0 ? 'inline-block' : 'none';
      };
    });
  </script>

  <!-- ================= SEARCH SUGGEST JS ================= -->
  <script>
    (function() {
      function ready(fn) {
        if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', fn);
        } else fn();
      }

      ready(function() {
        var form = document.querySelector('nav form[role="search"]');
        if (!form) return;

        var group = form.querySelector('.input-group');
        var icon = group && group.querySelector('.input-group-text');
        var input = form.querySelector('input[name="q"]');

        if (icon) {
          icon.style.cursor = 'pointer';
          icon.onclick = () => form.submit();
        }

        if (!input) return;

        var list = document.getElementById('navSearchSuggest');
        if (!list) {
          list = document.createElement('div');
          list.id = 'navSearchSuggest';
          list.className = 'search-suggest';
          form.appendChild(list);
        }

        function hide() {
          list.style.display = 'none';
        }

        function show() {
          list.style.display = 'block';
        }

        var ctrl, t;
        var origin = window.location.origin || '';

        function render(items) {
          if (!items.length) {
            hide();
            return;
          }
          list.innerHTML =
            items.map(it =>
              `<a href="${origin}/product/${it.id}">
            <i class="bi bi-box-seam me-2 text-primary"></i>${it.name}
           </a>`
            ).join('') +
            `<div class="footer"><a href="${origin}/products?q=${encodeURIComponent(input.value || '')}">Tìm "${input.value}"</a></div>`;
          show();
        }

        function fetchSuggest(q) {
          if (ctrl && ctrl.abort) ctrl.abort();
          if (!q) {
            hide();
            return;
          }

          try {
            ctrl = new AbortController();
          } catch (_) {
            ctrl = null;
          }

          fetch(`${origin}/api/search/products?q=${encodeURIComponent(q)}`, {
              signal: ctrl?.signal
            })
            .then(r => r.json())
            .then(d => render((d ?? []).slice(0, 8)))
            .catch(() => {});
        }

        input.oninput = () => {
          clearTimeout(t);
          let v = input.value.trim();
          t = setTimeout(() => fetchSuggest(v), 160);
        };

        input.onfocus = () => {
          let v = input.value.trim();
          if (v) fetchSuggest(v);
        };

        document.addEventListener('click', e => {
          if (!form.contains(e.target)) hide();
        });
      });
    })();
  </script>

  <!-- SCROLL TOP BUTTON -->
  <button id="scrollTopBtn" style="position:fixed; right:16px; bottom:18px; display:none;
        align-items:center; justify-content:center;
        width:44px; height:44px; border-radius:50%;
        border:none; background:linear-gradient(90deg, var(--blue-dark), var(--blue-main));
        color:#fff; box-shadow:0 8px 20px rgba(0,0,0,.18); z-index:1050;">
    <i class="bi bi-arrow-up"></i>
  </button>
  <style>
    /* ===================== CHATBOX PREMIUM – AQUASHOP ===================== */

    /* Nút mở chat */
    .chat-widget-toggle {
      position: fixed;
      right: 18px;
      bottom: 84px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: none;
      background: linear-gradient(135deg, #00bcd4, #009688);
      color: #fff;
      box-shadow: 0 12px 26px rgba(0, 0, 0, .25);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1060;
      transition: .25s ease;
    }

    .chat-widget-toggle:hover {
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0 16px 32px rgba(0, 0, 0, .28);
    }

    /* Khung chat */
    .chat-widget-panel {
      position: fixed;
      right: 18px;
      bottom: 150px;
      width: 360px;
      max-height: 520px;
      background: #ffffff;
      border-radius: 20px;
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 1059;
      box-shadow:
        0 20px 40px rgba(0, 0, 0, .25),
        0 6px 12px rgba(0, 0, 0, .12);
      animation: chatOpen .25s ease;
    }

    @keyframes chatOpen {
      from {
        opacity: 0;
        transform: translateY(10px) scale(.98);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @media (max-width: 576px) {
      .chat-widget-panel {
        right: 10px;
        left: 10px;
        width: auto;
      }
    }

    /* Header */
    .chat-widget-header {
      background: linear-gradient(120deg, #009688, #00acc1);
      color: #fff;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .chat-widget-header .title {
      font-weight: 800;
      font-size: 16px;
    }

    .chat-widget-header .subtitle {
      font-size: 11px;
      opacity: .9;
      margin-top: -2px;
    }

    /* Khu vực tin nhắn */
    .chat-widget-messages {
      padding: 14px 14px;
      overflow-y: auto;
      flex: 1;
      background: linear-gradient(180deg, #f2fbfb, #f6fefe);
    }

    /* Tin nhắn */
    .chat-msg {
      margin-bottom: 12px;
      display: flex;
      align-items: flex-end;
      gap: 8px;
    }

    .chat-msg.user {
      justify-content: flex-end;
    }

    .chat-msg.assistant {
      justify-content: flex-start;
    }

    /* Avatar (AI / User) */
    .chat-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
    }

    .chat-bubble {
      max-width: 78%;
      padding: 10px 14px;
      border-radius: 20px;
      font-size: 13px;
      line-height: 1.48;
      white-space: pre-wrap;
      animation: fadeIn .22s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(4px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Bubble user */
    .chat-msg.user .chat-bubble {
      background: #009688;
      color: #fff;
      border-bottom-right-radius: 6px;
      box-shadow: 0 2px 6px rgba(0, 150, 136, .35);
    }

    /* Bubble assistant */
    .chat-msg.assistant .chat-bubble {
      background: #fff;
      color: #222;
      border-bottom-left-radius: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    /* Bubble AI đang soạn */
    .typing-bubble {
      background: #ffffff;
      padding: 10px 14px;
      border-radius: 20px;
      display: inline-flex;
      gap: 5px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
    }

    .typing-dot {
      width: 7px;
      height: 7px;
      background: #888;
      border-radius: 50%;
      animation: typingAni 1s infinite ease-in-out;
    }

    .typing-dot:nth-child(2) {
      animation-delay: .2s;
    }

    .typing-dot:nth-child(3) {
      animation-delay: .4s;
    }

    @keyframes typingAni {

      0%,
      80%,
      100% {
        opacity: .3;
        transform: translateY(0);
      }

      40% {
        opacity: 1;
        transform: translateY(-3px);
      }
    }

    /* FOOTER – full width + không bị Bootstrap co lại */
    .chat-widget-footer {
      width: 100%;
      background: #fff;
      padding: 10px 12px;
      border-top: 1px solid #e6e6e6;

      display: flex;
      align-items: center;
    }

    /* ép form full width */
    .chat-widget-footer form {
      display: flex !important;
      align-items: center;
      width: 100% !important;
      gap: 10px !important;
      margin: 0;
    }

    /* input chiếm toàn bộ */
    .chat-widget-footer input {
      flex: 1 !important;
      width: 100% !important;
      height: 42px;

      border: 1px solid #dcdcdc;
      border-radius: 999px;
      padding: 0 16px;

      font-size: 13px;
      outline: none;
      background: #f9f9f9;
      transition: .2s;
    }

    .chat-widget-footer input:focus {
      background: #fff;
      border-color: #00bcd4 !important;
      box-shadow: 0 0 0 3px rgba(0, 188, 212, .15);
    }

    /* nút gửi */
    .chat-send-btn {
      height: 42px;
      padding: 0 18px;
      border-radius: 999px;

      background: linear-gradient(135deg, #009688, #00bcd4);
      border: none;
      color: #fff;
      font-size: 17px;

      display: flex;
      align-items: center;
      justify-content: center;

      box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
      transition: .18s;
    }

    .chat-send-btn:hover {
      transform: translateY(-2px) scale(1.05);
    }
  </style>


  <div id="chatWidgetPanel" class="chat-widget-panel">

    <div class="chat-widget-header">
      <div>
        <div class="title fw-bold">AquaShop Assistant</div>
        <div class="subtitle">Hỗ trợ 24/7</div>
      </div>
      <button type="button" class="btn btn-sm btn-light" id="chatWidgetClose">
        <i class="bi bi-x"></i>
      </button>
    </div>

    <div class="chat-widget-messages" id="chatMessages">

      <!-- Lời chào -->
      <div class="chat-msg assistant">
        <div class="chat-bubble">
          Xin chào 👋
          Mình là trợ lý thủy sinh AquaShop. Bạn muốn hỏi gì không?
        </div>
      </div>

    </div>

    <div class="chat-widget-footer">
      <form id="chatForm">
        <input type="text" id="chatInput" class="form-control form-control-sm" placeholder="Nhập câu hỏi...">
        <button class="chat-send-btn" type="submit"><i class="bi bi-send"></i></button>
      </form>
    </div>

  </div>

  <button class="chat-widget-toggle" id="chatWidgetToggle">
    <i class="bi bi-chat-dots-fill"></i>
  </button>

  <script>
    (function() {

      const panel = document.getElementById("chatWidgetPanel");
      const toggle = document.getElementById("chatWidgetToggle");
      const closeBt = document.getElementById("chatWidgetClose");

      const form = document.getElementById("chatForm");
      const input = document.getElementById("chatInput");
      const list = document.getElementById("chatMessages");

      const csrf = document.querySelector('meta[name="csrf-token"]').content;
      const askUrl = "{{ route('chatbot.ask') }}";

      /* =====================================================
           LƯU LỊCH SỬ CHAT LOCALSTORAGE
      ====================================================== */
      let history = JSON.parse(localStorage.getItem("aquashop_chat_history") || "[]");

      history.forEach(m => appendMessage(m.role, m.content, false));


      function saveHistory() {
        localStorage.setItem("aquashop_chat_history", JSON.stringify(history.slice(-20)));
      }

      /* =====================================================
            SCROLL & APPEND MESSAGE (fade mượt)
      ====================================================== */
      function scrollBottom() {
        list.scrollTop = list.scrollHeight;
      }

      function appendMessage(role, text, animate = true) {
        const wrap = document.createElement("div");
        wrap.className = "chat-msg " + role;

        const bubble = document.createElement("div");
        bubble.className = "chat-bubble";
        bubble.textContent = text;

        if (animate) {
          bubble.style.opacity = "0";
          bubble.style.transform = "translateY(6px)";
          bubble.style.transition = "all .28s ease";
          setTimeout(() => {
            bubble.style.opacity = "1";
            bubble.style.transform = "translateY(0)";
          }, 25);
        }

        wrap.appendChild(bubble);
        list.appendChild(wrap);
        scrollBottom();
      }

      /* =====================================================
            TYPING AI – 3 CHẤM NHẤP NHÁY
      ====================================================== */
      let typingAi = null;

      function showTypingAI() {
        hideTypingAI();
        typingAi = document.createElement("div");
        typingAi.className = "chat-msg assistant";
        typingAi.innerHTML = `
      <div class="typing-bubble">
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
      </div>
    `;
        list.appendChild(typingAi);
        scrollBottom();
      }

      function hideTypingAI() {
        if (typingAi) typingAi.remove();
        typingAi = null;
      }

      /* =====================================================
          HIỆU ỨNG “ĐANG GỬI…” CỦA NGƯỜI DÙNG
      ====================================================== */
      let sendingEl = null;

      function hideSendingUser() {
        if (sendingEl) sendingEl.remove();
        sendingEl = null;
      }

      /* =====================================================
            TOGGLE PANEL – auto scroll to bottom
      ====================================================== */
      toggle.addEventListener("click", () => {
        const isOpen = panel.style.display === "flex";
        panel.style.display = isOpen ? "none" : "flex";

        if (!isOpen) {
          setTimeout(() => scrollBottom(), 120);
          setTimeout(() => input.focus(), 150);
        }
      });

      closeBt.addEventListener("click", () => panel.style.display = "none");


      /* =====================================================
          ĐÓNG CHATBOX KHI CLICK RA NGOÀI
      ====================================================== */
      document.addEventListener("click", e => {
        if (!panel.contains(e.target) && !toggle.contains(e.target)) {
          panel.style.display = "none";
        }
      });

      /* =====================================================
            SEND EVENT
      ====================================================== */
      form.addEventListener("submit", e => {
        e.preventDefault();

        let msg = input.value.trim();
        if (!msg) return;

        appendMessage("user", msg, true);
        history.push({
          role: "user",
          content: msg
        });
        saveHistory();
        input.value = "";

        // AI typing effect
        setTimeout(showTypingAI, 320);

        fetch(askUrl, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrf
            },
            body: JSON.stringify({
              message: msg,
              history: history.slice(-6)
            })
          })
          .then(r => r.json())
          .then(d => {
            hideSendingUser();
            hideTypingAI();

            let reply = d.reply || "Xin lỗi, hiện tôi không thể trả lời.";
            appendMessage("assistant", reply, true);

            history.push({
              role: "assistant",
              content: reply
            });
            saveHistory();
          })
          .catch(() => {
            hideSendingUser();
            hideTypingAI();
            appendMessage("assistant", "Mạng lỗi… vui lòng thử lại.", true);
          });
      });

    })();
  </script>

</body>

</html>