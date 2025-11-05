@extends('layouts.admin')
@section('title','Thương hiệu')
@section('content')

<div class="admin-headerbar">
  <h3>Thương hiệu</h3>
  <div>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-ocean"><i class="bi bi-plus-lg me-1"></i>Thêm thương hiệu</a>
  </div>
</div>

<form class="row g-2 mb-3" method="GET">
  <div class="col-md-4">
    <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Tìm theo tên...">
  </div>
  <div class="col-md-2"><button class="btn btn-outline-ocean w-100">Tìm</button></div>
</form>

<div class="card p-3">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>#</th><th>Tên</th><th>Slug</th><th class="text-end">Thao tác</th></tr></thead>
      <tbody>
        @forelse($brands as $b)
          <tr>
            <td>{{ $b->id }}</td>
            <td>{{ $b->name }}</td>
            <td class="text-muted">{{ $b->slug }}</td>
            <td class="text-end">
              <a href="{{ route('admin.brands.edit', $b->id) }}" class="btn btn-sm btn-outline-ocean">Sửa</a>
              <form method="POST" action="{{ route('admin.brands.destroy', $b->id) }}" class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted">Chưa có thương hiệu.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div>{{ $brands->links() }}</div>
</div>
@endsection

