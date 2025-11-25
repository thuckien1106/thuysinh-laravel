@if(session('success') && !request()->routeIs('cart') && !session('_flash_shown'))
  <div class="alert alert-success alert-ocean alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
  </div>
  @php session(['_flash_shown' => true]) @endphp
@endif

@if(session('error') && !session('_flash_error_shown'))
  <div class="alert alert-danger alert-ocean alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
  </div>
  @php session(['_flash_error_shown' => true]) @endphp
@endif

@if(session('warning') && !session('_flash_warning_shown'))
  <div class="alert alert-warning alert-ocean alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
  </div>
  @php session(['_flash_warning_shown' => true]) @endphp
@endif

@if($errors->any() && !session('_flash_errors_shown'))
  <div class="alert alert-danger alert-ocean alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
  </div>
  @php session(['_flash_errors_shown' => true]) @endphp
@endif

<script>
  (function(){
    // Auto-dismiss alerts after 3s
    const autoClose = function(){
      // Auto-close all alerts (including errors)
      document.querySelectorAll('.alert.alert-dismissible').forEach(function(el){
        if (el.dataset.autoclosed) return;
        el.dataset.autoclosed = '1';
        setTimeout(function(){
          try {
            (window.bootstrap && window.bootstrap.Alert ? window.bootstrap.Alert.getOrCreateInstance(el) : null)?.close();
          } catch(_) { el.classList.remove('show'); el.remove(); }
        }, 3000);
      });
    };
    if (document.readyState === 'complete') autoClose();
    else window.addEventListener('load', autoClose);
  })();
  </script>
