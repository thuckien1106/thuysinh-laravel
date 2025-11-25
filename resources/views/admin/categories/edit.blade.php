@extends('layouts.admin')
@section('title','Cập nhật danh mục')
@section('content')

<style>
    /* ================= EDIT CATEGORY THEME ================= */
    :root {
        --card-radius: 12px;
        --border-color: #e2e8f0;
        --primary-color: #3b82f6; /* Blue for Edit actions */
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .form-card-header {
        padding: 16px 24px;
        background-color: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
    }
    .form-card-title {
        font-weight: 700;
        color: #334155;
        margin: 0;
        font-size: 1rem;
    }
    .form-card-body {
        padding: 24px;
    }

    /* Input Styling */
    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Button Styling */
    .btn-back {
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        color: #64748b;
        background: white;
        border: 1px solid #cbd5e1;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-back:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
    }
    .btn-submit {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        transition: transform 0.2s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Cập nhật danh mục</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}" class="text-muted text-decoration-none">Danh mục</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sửa #{{ $category->id }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.categories.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Quay lại
        </a>
    </div>
</div>

{{-- ERROR ALERT --}}
@if ($errors->any())
    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <h6 class="fw-bold mb-0">Vui lòng kiểm tra lại dữ liệu:</h6>
        </div>
        <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- FORM CARD --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        <h5 class="form-card-title">Thông tin danh mục</h5>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
            @csrf @method('PUT')
            
            <div class="row g-4">
                <div class="col-md-8">
                    <label class="form-label required">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control fw-semibold" 
                           value="{{ old('name', $category->name) }}" 
                           placeholder="Nhập tên danh mục..." required>
                    <div class="form-text mt-2 text-muted">
                        Tên danh mục sẽ hiển thị trên menu và các trang sản phẩm.
                    </div>
                </div>

                {{-- Nếu sau này có thêm ảnh đại diện danh mục hay mô tả thì thêm cột vào đây --}}
                
                <div class="col-12 pt-3 border-top mt-4 text-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light border me-2 rounded-3 px-4 fw-bold text-muted">Hủy</a>
                    <button class="btn btn-submit">
                        <i class="bi bi-check2-circle me-1"></i> Cập nhật
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection