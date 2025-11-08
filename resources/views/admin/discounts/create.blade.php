@extends('layouts.admin')
@section('title','Thêm giảm giá')
@section('content')
<div class="admin-headerbar">
  <h3>Thêm giảm giá</h3>
  <div><a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-ocean">Quay lại</a></div>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Vui lòng kiểm tra lại:</div>
    <ul class="mb-0">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card p-3">
  @include('admin.discounts._form', [
    'action' => route('admin.discounts.store'),
    'method' => 'POST',
    'products' => $products,
    'discount' => null,
    'readonlyProduct' => false,
  ])
</div>
@endsection

