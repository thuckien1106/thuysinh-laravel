<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle d-flex align-items-center"
     href="#" id="userMenu" role="button" data-bs-toggle="dropdown">

     <i class="bi bi-person-circle me-1 fs-5"></i>
     <strong>{{ session('admin')->username }}</strong>
  </a>

  <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">

    @if((session('admin')->role ?? 'user') === 'user')
      <li><a class="dropdown-item" href="{{ route('account.profile') }}">
        <i class="bi bi-person-gear me-2 text-primary"></i>Thông tin tài khoản
      </a></li>

      <li><a class="dropdown-item" href="{{ route('orders.mine') }}">
        <i class="bi bi-receipt-cutoff me-2 text-success"></i>Đơn hàng của tôi
      </a></li>

      <li><hr class="dropdown-divider"></li>
    @endif

    @if(session('admin')->role === 'admin')
      <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2 text-warning"></i>Trang quản trị
      </a></li>

      <li><hr class="dropdown-divider"></li>
    @endif

    <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
      <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
    </a></li>

  </ul>
</li>
