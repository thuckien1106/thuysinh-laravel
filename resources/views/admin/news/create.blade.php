@extends('layouts.admin')

@section('title', 'Thêm tin tức')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Thêm tin tức</h4>
  <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.news.store') }}">
      @csrf
      @include('admin.news.partials.form', ['news' => null])
      <button class="btn btn-primary">Lưu</button>
    </form>
  </div>
</div>
@endsection
