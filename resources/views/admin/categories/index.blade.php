@extends('layouts.admin')
@section('title','Quản lý danh mục')
@section('content')

<style>
    /* ================= CATEGORY PAGE THEME ================= */
    :root {
        --table-header-bg: #f8fafc;
        --border-color: #e2e8f0;
        --primary-color: #3b82f6;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
    }
    .search-input {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .search-input:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
    }

    /* Table Styling */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .category-table th {
        background-color: var(--table-header-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #475569;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .category-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
    }
    
    .category-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Button Styling */
    .btn-create {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        transition: transform 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        color: #64748b;
        background: transparent;
        border: 1px solid transparent;
    }
    .action-btn:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .btn-delete:hover {
        background-color: #fee2e2;
        color: #ef4444;
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Danh mục sản phẩm</h3>
        <small class="text-muted">Quản lý các nhóm sản phẩm</small>
    </div>
    <div>
        <a href="{{ route('admin.categories.create') }}" class="btn-create">
            <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
        </a>
    </div>
</div>

{{-- FILTER SECTION --}}
<div class="filter-card">
    <form class="row g-3 align-items-end" method="GET">
        <div class="col-md-5">
            <span class="filter-label">Tìm kiếm</span>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control search-input border-start-0 ps-0" name="q" value="{{ $q }}" placeholder="Nhập tên danh mục...">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; height: 42px;">Tìm kiếm</button>
        </div>
    </form>
</div>

{{-- TABLE SECTION --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table category-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 10%">#ID</th>
                    <th>Tên danh mục</th>
                    <th class="text-end pe-4" style="width: 20%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">#{{ $c->id }}</td>
                        <td class="fw-semibold text-dark">{{ $c->name }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.edit', $c->id) }}" class="action-btn" title="Chỉnh sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm thuộc danh mục có thể bị ảnh hưởng.')">
                                    @csrf @method('DELETE')
                                    <button class="action-btn btn-delete" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-folder2-open fs-1 text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted fw-bold">Chưa có danh mục nào</h6>
                                <p class="text-muted small mb-0">Hãy thêm danh mục mới để phân loại sản phẩm.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="p-3 border-top">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection