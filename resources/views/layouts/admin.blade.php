<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'AquaShop Admin')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('assets/css/admin.css') }}?v={{ @filemtime(public_path('assets/css/admin.css')) }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
  <style>body{font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif}</style>
  <style>
    /* Bảo đảm lịch nổi trên modal và không bị che */
    .flatpickr-calendar { z-index: 200000 !important; }
    /* Thu nhỏ lịch ~20% và giữ neo ở góc trên-trái để canh vị trí ổn định */
    .flatpickr-calendar { transform: none !important; width: 320px; font-size: 14px; }
    /* Kéo modal giảm giá lên gần đỉnh màn hình (xấp xỉ 30% so với vị trí giữa) */
    #discountModal .modal-dialog { align-self: flex-start; margin-top: 10vh; }
  </style>
  @stack('styles')
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
      <a class="nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}"><i class="bi bi-award me-2"></i>Thương hiệu</a>
      <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-grid-3x3-gap me-2"></i>Danh mục</a>
      <a class="nav-link {{ request()->is('admin/discounts*') ? 'active' : '' }}" href="{{ route('admin.discounts.index') }}"><i class="bi bi-percent me-2"></i>Giảm giá</a>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.js"></script>
<script>
(function(){
  const opts = {
    enableTime: true,
    time_24hr: true,
    dateFormat: 'Y-m-d H:i',
    altInput: false, // tránh tạo thêm input gây lệch layout trong input-group
    minuteIncrement: 5,
    appendTo: document.body, // gắn lên body để tránh overflow hidden trong modal
    static: false,
    position: 'below',
    disableMobile: true,
    locale: (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns.vn) || 'vn'
  };

  function safePosition(instance){
    try {
      setTimeout(function(){
        const cal = instance.calendarContainer;
        if (!cal) return;
        const rect = cal.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.top;
        const need = rect.height + 16;
        const desired = (spaceBelow < need) ? 'above' : 'below';
        if (instance.config.position !== desired) {
          instance.set('position', desired);
          instance._positionCalendar();
        }
      }, 0);
    } catch(e) { /* no-op */ }
  }

  function buildOptions(){
    const plugins = [];
    if (window.confirmDatePlugin) {
      plugins.push(window.confirmDatePlugin({
        showAlways: true,
        theme: 'light',
        confirmText: 'Chọn ✓'
      }));
    }
    return Object.assign({}, opts, {
      plugins: plugins,
      onReady: [onReadyHook, function(sel, str, inst){ safePosition(inst); }],
      onOpen: [function(sel, str, inst){ safePosition(inst); }],
      onMonthChange: [function(sel, str, inst){ safePosition(inst); }],
      onYearChange: [function(sel, str, inst){ safePosition(inst); }]
    });
  }

  function onReadyHook(selectedDates, dateStr, instance){
    try {
      const cal = instance.calendarContainer;
      if (!cal || cal.querySelector('.fp-btn-today')) return;
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn btn-sm btn-link fp-btn-today';
      btn.textContent = 'Hôm nay';
      btn.addEventListener('click', function(){
        instance.setDate(new Date(), true);
      });
      // Ưu tiên đặt gần nút xác nhận nếu có
      const confirmWrap = cal.querySelector('.flatpickr-confirm');
      if (confirmWrap && confirmWrap.parentNode) {
        confirmWrap.parentNode.insertBefore(btn, confirmWrap);
      } else {
        cal.appendChild(btn);
      }
    } catch(e) { /* no-op */ }
  }

  function initDiscountDatetime(container){
    if (!window.flatpickr) return;
    const scope = container || document;
    scope.querySelectorAll('input.js-dtp').forEach(function(el){
      if (el._flatpickr) return;
      // Nếu có nút icon tương ứng, đặt positionElement để lịch chui dưới icon
      let posEl = null;
      const id = el.getAttribute('id');
      if (id) {
        const btn = scope.querySelector('[data-open="'+id+'"]') || document.querySelector('[data-open="'+id+'"]');
        if (btn) posEl = btn;
      }
      const opt = Object.assign({}, buildOptions(), { defaultDate: el.value || null });
      if (posEl) opt.positionElement = posEl;
      flatpickr(el, opt);
    });

    // Clamp end >= start if both present in same scope
    const s = scope.querySelector('#start_at');
    const e = scope.querySelector('#end_at');
    if (s && e && s._flatpickr && e._flatpickr) {
      const fpStart = s._flatpickr, fpEnd = e._flatpickr;
      const clamp = function(){
        const sd = fpStart.selectedDates[0];
        const ed = fpEnd.selectedDates[0];
        if (sd && ed && ed < sd) fpEnd.setDate(sd, true);
      };
      fpStart.config.onChange.push(clamp);
      fpEnd.config.onChange.push(clamp);
    }
  }

  window.initDiscountDatetime = initDiscountDatetime;

  document.addEventListener('DOMContentLoaded', function(){ initDiscountDatetime(document); });
  document.addEventListener('shown.bs.modal', function(e){ initDiscountDatetime(e.target); });
  window.addEventListener('resize', function(){
    document.querySelectorAll('.flatpickr-input').forEach(function(inp){
      if (inp._flatpickr) safePosition(inp._flatpickr);
    });
  });

  document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-open]');
    if (!btn) return;
    const id = btn.getAttribute('data-open');
    const scope = btn.closest('.modal') || document;
    const inp = scope.getElementById ? scope.getElementById(id) : document.getElementById(id);
    if (inp && inp._flatpickr) inp._flatpickr.open();
  });
})();
</script>
<script>
// Override to anchor calendar to the icon and open above
(function(){
  function overrideInit(container){
    if (!window.flatpickr) return;
    const scope = container || document;
    const optsBase = {
      enableTime: true,
      time_24hr: true,
      dateFormat: 'Y-m-d H:i',
      altInput: false,
      minuteIncrement: 1,
      allowInput: true,
      appendTo: document.body,
      static: false,
      position: 'above',
      disableMobile: true,
      locale: (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns.vn) || 'vn'
    };

    function addToday(instance){
      try {
        const cal = instance && instance.calendarContainer;
        if (!cal || cal.querySelector('.fp-btn-today')) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-link fp-btn-today';
        btn.textContent = 'Hom nay';
        btn.addEventListener('click', function(){ instance.setDate(new Date(), true); });
        const confirmWrap = cal.querySelector('.flatpickr-confirm');
        if (confirmWrap && confirmWrap.parentNode) confirmWrap.parentNode.insertBefore(btn, confirmWrap);
        else cal.appendChild(btn);
      } catch(_) { /* noop */ }
    }

    function addCancel(instance){
      try {
        const cal = instance && instance.calendarContainer;
        if (!cal || cal.querySelector('.fp-btn-cancel')) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-link text-danger fp-btn-cancel';
        btn.title = 'Thoat';
        btn.textContent = 'Thoat ✕';
        btn.addEventListener('click', function(){
          try {
            const oldVal = instance._origValue ?? '';
            if (oldVal) instance.setDate(oldVal, true);
            else instance.clear();
          } catch(_){}
          instance.close();
        });
        const confirmWrap = cal.querySelector('.flatpickr-confirm');
        if (confirmWrap && confirmWrap.parentNode) confirmWrap.parentNode.insertBefore(btn, confirmWrap);
        else cal.appendChild(btn);
      } catch(_) { /* noop */ }
    }
    scope.querySelectorAll('input.js-dtp').forEach(function(el){
      if (el._flatpickr){
        try {
          const id = el.getAttribute('id');
          const btn = id ? (scope.querySelector('[data-open="'+id+'"]') || document.querySelector('[data-open="'+id+'"]')) : null;
          el._flatpickr.set('positionElement', btn || el);
          el._flatpickr.set('position','above');
        } catch(_) {}
        return;
      }
      const id = el.getAttribute('id');
      const btn = id ? (scope.querySelector('[data-open="'+id+'"]') || document.querySelector('[data-open="'+id+'"]')) : null;
      // Default date: now for start_at, now+1h for end_at, only when empty
      let defaultDate = null;
      if (!el.value || !el.value.trim()) {
        defaultDate = (id === 'end_at') ? new Date(Date.now() + 60*60*1000) : new Date();
      } else {
        defaultDate = el.value;
      }
      const opt = Object.assign({}, optsBase, {
        defaultDate: defaultDate,
        positionElement: btn || el,
        onReady: [function(_, __, inst){ addToday(inst); addCancel(inst); }],
        onOpen: [function(_, __, inst){ try{ inst._origValue = inst.input ? inst.input.value : ''; }catch(_){} addCancel(inst);}]
      });
      if (window.confirmDatePlugin){
        opt.plugins = [ window.confirmDatePlugin({ showAlways:true, theme:'light' }) ];
      }
      const fp = flatpickr(el, opt);
      addToday(fp); addCancel(fp);
    });

    // Keep end >= start
    const s = scope.querySelector('#start_at');
    const e = scope.querySelector('#end_at');
    if (s && e && s._flatpickr && e._flatpickr){
      const fpStart = s._flatpickr, fpEnd = e._flatpickr;
      const clamp = function(){
        const sd = fpStart.selectedDates[0];
        const ed = fpEnd.selectedDates[0];
        if (sd && ed && ed < sd) fpEnd.setDate(sd, true);
      };
      fpStart.config.onChange.push(clamp);
      fpEnd.config.onChange.push(clamp);

      // Realtime status: active vs passed
      function ensureHint(forId){
        const input = (forId==='start_at') ? s : e;
        let holder = input.closest('.input-group').nextElementSibling;
        // if next sibling is not a hint, create it
        if (!holder || !holder.matches('.form-text[data-hint]')){
          holder = document.createElement('div');
          holder.className = 'form-text';
          holder.setAttribute('data-hint', forId);
          input.closest('.input-group').insertAdjacentElement('afterend', holder);
        }
        return holder;
      }

      function parseLocal(val){
        if (!val) return null;
        // value is 'YYYY-MM-DD HH:ii'
        const iso = val.replace(' ', 'T');
        const d = new Date(iso);
        return isNaN(d.getTime()) ? null : d;
      }

      function updateHints(){
        const now = new Date();
        const sVal = s.value; const eVal = e.value;
        const sDate = s._flatpickr && s._flatpickr.selectedDates[0] ? s._flatpickr.selectedDates[0] : parseLocal(sVal);
        const eDate = e._flatpickr && e._flatpickr.selectedDates[0] ? e._flatpickr.selectedDates[0] : parseLocal(eVal);

        const startHint = ensureHint('start_at');
        const endHint = ensureHint('end_at');

        // reset classes
        [startHint, endHint].forEach(h=>{ h.classList.remove('text-success','text-danger','text-warning'); });

        if (sDate){
          if (sDate <= now) { startHint.textContent = 'Hiệu lực'; startHint.classList.add('text-success'); }
          else { startHint.textContent = 'Chưa đến giờ'; startHint.classList.add('text-warning'); }
        }

        if (eDate){
          if (eDate < now) { endHint.textContent = 'Đã qua'; endHint.classList.add('text-danger'); }
          else { endHint.textContent = 'Hiệu lực'; endHint.classList.add('text-success'); }
        }

        // If both present and now between -> both show Hiệu lực
        if (sDate && eDate){
          if (sDate <= now && now <= eDate){
            startHint.textContent = 'Hiệu lực'; startHint.classList.remove('text-warning','text-danger'); startHint.classList.add('text-success');
            endHint.textContent = 'Hiệu lực'; endHint.classList.remove('text-warning','text-danger'); endHint.classList.add('text-success');
          }
        }
      }

      updateHints();
      fpStart.config.onChange.push(updateHints);
      fpEnd.config.onChange.push(updateHints);
    }
  }
  window.initDiscountDatetime = overrideInit;
})();
</script>
@stack('scripts')
</body>
</html>
