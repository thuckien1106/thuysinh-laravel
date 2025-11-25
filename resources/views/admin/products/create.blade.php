@extends('layouts.admin')
@section('title', 'Thêm sản phẩm mới')
@section('content')

<style>
    /* ================= FORM THEME ================= */
    :root {
        --input-radius: 10px;
        --input-bg: #fff;
        --border-color: #e2e8f0;
        --primary-color: #10b981; /* Emerald Green */
    }

    /* Card bao bọc form */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .form-card-header {
        padding: 16px 24px;
        background-color: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 700;
        color: #334155;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }
    .form-card-body {
        padding: 24px;
    }

    /* Style cho input */
    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: var(--input-radius);
        border: 1px solid var(--border-color);
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .form-text {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Upload vùng ảnh */
    .image-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }
    .image-upload-zone:hover {
        border-color: var(--primary-color);
        background: #f0fdf4;
    }
    .image-upload-zone input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Các nút bấm */
    .btn-action {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: transform 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

{{-- Header & Actions --}}
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Thêm sản phẩm mới</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo mới</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-action me-2">
                Hủy bỏ
            </a>
            <button type="submit" class="btn btn-success btn-action text-white" style="background-color: #10b981; border: none;">
                <i class="bi bi-floppy me-1"></i> Lưu sản phẩm
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="row g-4">
        {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
        <div class="col-lg-8">
            
            <!-- 1. Thông tin chung -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Thông tin chung
                </div>
                <div class="form-card-body">
                    <div class="mb-3">
                        <label class="form-label required">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên sản phẩm..." required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn (SEO)</label>
                        <textarea class="form-control" name="short_description" rows="2" placeholder="Tóm tắt sản phẩm trong 1-2 câu (tối đa 255 ký tự)..."></textarea>
                        <div class="form-text">Hiển thị ở danh sách sản phẩm và kết quả tìm kiếm.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="long_description" rows="8" placeholder="Viết mô tả chi tiết về nguồn gốc, đặc điểm, công dụng..."></textarea>
                    </div>
                </div>
            </div>

            <!-- 2. Thông số & Hướng dẫn -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-journal-text me-2 text-primary"></i> Chi tiết kỹ thuật
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Thông số kỹ thuật</label>
                            <textarea class="form-control" name="specs" rows="6" placeholder="- Kích thước: ...&#10;- Nhiệt độ: ...&#10;- pH: ..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hướng dẫn chăm sóc</label>
                            <textarea class="form-control" name="care_guide" rows="6" placeholder="- Thay nước: ...&#10;- Ánh sáng: ...&#10;- Dinh dưỡng: ..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- CỘT PHẢI: THÔNG TIN PHỤ & MEDIA --}}
        <div class="col-lg-4">
            
            <!-- 3. Giá & Kho -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-coin me-2 text-warning"></i> Giá bán & Kho hàng
                </div>
                <div class="form-card-body">
                    <div class="mb-3">
                        <label class="form-label required">Giá bán (VNĐ)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" max="99999999.99" class="form-control fw-bold" name="price" placeholder="0" required>
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label required">Số lượng tồn kho</label>
                        <input type="number" class="form-control" name="quantity" value="1" min="0" required>
                    </div>
                </div>
            </div>

            <!-- 4. Phân loại -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-tags me-2 text-info"></i> Phân loại
                </div>
                <div class="form-card-body">
                    <div class="mb-3">
                        <label class="form-label required">Danh mục</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Thương hiệu</label>
                        <select class="form-select" name="brand_id">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- 5. Hình ảnh -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-image me-2 text-success"></i> Hình ảnh
                </div>
                <div class="form-card-body">
                    <label class="form-label">Ảnh đại diện</label>
                    
                    {{-- Upload file --}}
                    <div class="image-upload-zone mb-3">
                        <div class="text-center py-3">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                            <p class="mb-0 fw-semibold text-secondary mt-2">Kéo thả hoặc click để tải ảnh</p>
                            <small class="text-muted">Hỗ trợ: JPG, PNG, WEBP</small>
                        </div>
                        <input type="file" name="image_file" accept="image/*" onchange="previewImage(this)">
                    </div>

                    {{-- Preview Area --}}
                    <div id="imagePreview" class="text-center d-none mb-3">
                        <img src="" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none mt-1" onclick="clearImage()">Xóa ảnh</button>
                    </div>

                    {{-- Nhập tên file (Legacy) --}}
                    <div class="border-top pt-3 mt-3">
                        <label class="form-label small text-muted">Hoặc nhập tên file có sẵn (assets/img):</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-link-45deg"></i></span>
                            <input class="form-control" name="image" placeholder="vd: reu_java.webp">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        const zone = document.querySelector('.image-upload-zone');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('d-none');
                zone.classList.add('d-none'); // Ẩn vùng upload đi cho gọn
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImage() {
        const input = document.querySelector('input[name="image_file"]');
        const preview = document.getElementById('imagePreview');
        const zone = document.querySelector('.image-upload-zone');
        
        input.value = ''; // Reset input file
        preview.classList.add('d-none');
        zone.classList.remove('d-none');
    }
</script>

@endsection