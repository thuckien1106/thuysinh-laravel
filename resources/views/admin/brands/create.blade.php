@extends('layouts.admin')
@section('title','Thêm thương hiệu mới')
@section('content')

<style>
    /* ================= CREATE BRAND THEME ================= */
    :root {
        --card-radius: 12px;
        --border-color: #e2e8f0;
        --primary-color: #10b981; /* Green for Create actions */
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
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .form-text {
        font-size: 0.8rem;
        color: #94a3b8;
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
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        transition: transform 0.2s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Thêm thương hiệu</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}" class="text-muted text-decoration-none">Thương hiệu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tạo mới</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.brands.index') }}" class="btn-back">
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
        <i class="bi bi-plus-circle me-2 text-success"></i>
        <h5 class="form-card-title">Thông tin thương hiệu mới</h5>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.brands.store') }}">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label required">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="Nhập tên thương hiệu..." required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug (Đường dẫn)</label>
                    <input type="text" name="slug" class="form-control" 
                           placeholder="tự-động-tạo-nếu-để-trống">
                    <div class="form-text mt-1">
                        Dùng để tạo đường dẫn thân thiện (SEO).
                    </div>
                </div>
                
                <div class="col-12 pt-3 border-top mt-4 text-end">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-light border me-2 rounded-3 px-4 fw-bold text-muted">Hủy</a>
                    <button class="btn btn-submit">
                        <i class="bi bi-save me-1"></i> Lưu thương hiệu
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection