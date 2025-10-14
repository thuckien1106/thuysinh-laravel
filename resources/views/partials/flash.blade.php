@if(session('success') && !request()->routeIs('cart'))
  <div class="alert alert-success alert-ocean" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-ocean" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
  </div>
@endif

@if(session('warning'))
  <div class="alert alert-warning alert-ocean" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-ocean" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
  </div>
@endif
