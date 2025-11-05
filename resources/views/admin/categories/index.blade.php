@extends('layouts.admin')
@section('title','Danh mục')
@section('content')

<div class="admin-headerbar">
  <h3>Danh mục</h3>
  <div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-ocean"><i class="bi bi-plus-lg me-1"></i>Thêm danh mục</a>
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
      <thead><tr><th>#</th><th>Tên danh mục</th><th class="text-end">Thao tác</th></tr></thead>
      <tbody>
        @forelse($categories as $c)
          <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td class="text-end">
              <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-sm btn-outline-ocean">Sửa</a>
              <form method="POST" action="{{ route('admin.categories.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Chưa có danh mục.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div>{{ $categories->links() }}</div>
</div>
@endsection

