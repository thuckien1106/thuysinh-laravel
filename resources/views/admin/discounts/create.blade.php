@extends('layouts.admin')
@section('title','Thêm giảm giá mới')
@section('content')

<style>
    /* ================= FORM PAGE THEME ================= */
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
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Thêm giảm giá</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}" class="text-muted text-decoration-none">Quản lý giảm giá</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tạo mới</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.discounts.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
        </a>
    </div>
</div>

{{-- ERROR ALERT --}}
@if ($errors->any())
    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
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
        <i class="bi bi-tag-fill me-2 text-success"></i>
        <h5 class="form-card-title">Thông tin chương trình khuyến mãi</h5>
    </div>
    <div class="form-card-body">
        @include('admin.discounts._form', [
            'action' => route('admin.discounts.store'),
            'method' => 'POST',
            'products' => $products,
            'discount' => null,
            'readonlyProduct' => false,
        ])
    </div>
</div>

@endsection