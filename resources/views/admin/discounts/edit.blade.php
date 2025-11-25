@extends('layouts.admin')
@section('title','Cập nhật giảm giá')
@section('content')

<style>
    /* ================= EDIT FORM THEME ================= */
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

    /* Input & Select */
    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
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
        <h3 class="fw-bold m-0 text-dark">Cập nhật giảm giá</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}" class="text-muted text-decoration-none">Quản lý giảm giá</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sửa #{{ $discount->id }}</li>
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
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <h6 class="fw-bold mb-0">Vui lòng kiểm tra lại:</h6>
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
        <h5 class="form-card-title">Thông tin chương trình</h5>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.discounts.update', $discount->id) }}" class="row g-4">
            @csrf @method('PUT')
            
            <div class="col-md-8">
                <label class="form-label">Sản phẩm áp dụng <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select" required>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" @if($p->id == $discount->product_id) selected @endif>
                            {{ $p->name }} - {{ number_format($p->price, 0, ',', '.') }}đ
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Phần trăm giảm (%) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="1" max="90" name="percent" class="form-control fw-bold text-primary" value="{{ $discount->percent }}" required>
                    <span class="input-group-text bg-light text-muted">%</span>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                <input type="datetime-local" name="start_at" class="form-control" 
                       value="{{ old('start_at', optional($discount->start_at)->format('Y-m-d\\TH:i')) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                <input type="datetime-local" name="end_at" class="form-control" 
                       value="{{ old('end_at', optional($discount->end_at)->format('Y-m-d\\TH:i')) }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Ghi chú (Tùy chọn)</label>
                <textarea name="note" class="form-control" rows="2" placeholder="Nhập ghi chú nội bộ...">{{ $discount->note }}</textarea>
            </div>

            <div class="col-12 text-end pt-3 border-top mt-4">
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-light border me-2 rounded-3 px-4 fw-bold text-muted">Hủy</a>
                <button class="btn btn-submit">
                    <i class="bi bi-check2-circle me-1"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

@endsection