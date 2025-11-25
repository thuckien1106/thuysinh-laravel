@extends('layouts.admin')

@section('title', 'Sửa tin tức')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Sửa tin: {{ $news->title }}</h4>
  <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.news.update', $news) }}">
      @csrf
      @method('PUT')
      @include('admin.news.partials.form', ['news' => $news])
      <button class="btn btn-primary">Cập nhật</button>
    </form>
  </div>
</div>
@endsection
