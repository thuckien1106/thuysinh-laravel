@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Tin tức</h4>
  <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle"></i> Thêm tin
  </a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Tiêu đề</th>
            <th>Trạng thái</th>
            <th>Xuất bản</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            <tr>
              <td>
                <div class="fw-semibold">{{ $it->title }}</div>
                <div class="text-muted small">{{ $it->slug }}</div>
              </td>
              <td>
                @if($it->is_published)
                  <span class="badge bg-success">Đã đăng</span>
                @else
                  <span class="badge bg-secondary">Nháp</span>
                @endif
              </td>
              <td>{{ $it->published_at ? $it->published_at->format('d/m/Y H:i') : '—' }}</td>
              <td class="text-end">
                <a href="{{ route('admin.news.edit', $it) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.news.destroy', $it) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tin này?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted">Chưa có tin tức.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $items->links() }}
    </div>
  </div>
</div>
@endsection
