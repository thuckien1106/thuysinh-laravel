@extends('layouts.admin')
@section('title', 'Cập nhật tin tức')

@section('content')

<style>
    /* ================= EDIT NEWS THEME ================= */
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
        display: inline-flex;
        align-items: center;
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
        <h3 class="fw-bold m-0 text-dark">Cập nhật tin tức</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}" class="text-muted text-decoration-none">Tin tức</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sửa bài viết</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.news.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Quay lại
        </a>
    </div>
</div>

{{-- FORM CARD --}}
<div class="form-card">
    <div class="form-card-header">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        <h5 class="form-card-title">Chỉnh sửa: {{ $news->title }}</h5>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            {{-- Include Form Partial --}}
            @include('admin.news.partials.form', ['news' => $news])
            
            <div class="col-12 pt-3 border-top mt-4 text-end">
                <a href="{{ route('admin.news.index') }}" class="btn btn-light border me-2 rounded-3 px-4 fw-bold text-muted">Hủy</a>
                <button class="btn btn-submit">
                    <i class="bi bi-check2-circle me-1"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

@endsection