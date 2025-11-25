@extends('layouts.admin')
@section('title', 'Cập nhật sản phẩm')
@section('content')

<style>
    /* ================= FORM THEME (Đồng bộ với trang Create) ================= */
    :root {
        --input-radius: 10px;
        --input-bg: #fff;
        --border-color: #e2e8f0;
        --primary-color: #3b82f6; /* Blue for Edit mode */
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
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-text {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Upload vùng ảnh */
    .image-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }
    .image-upload-zone:hover {
        border-color: var(--primary-color);
        background: #eff6ff;
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

    /* Current Image Container */
    .current-img-container {
        position: relative;
        display: inline-block;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .current-img-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background: rgba(0,0,0,0.6);
        color: white;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 4px;
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
<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Cập nhật sản phẩm</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $product->id }} - {{ \Illuminate\Support\Str::limit($product->name, 20) }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-action me-2">
                Hủy bỏ
            </a>
            <button type="submit" class="btn btn-primary btn-action text-white border-0" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <i class="bi bi-check2-circle me-1"></i> Lưu thay đổi
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
                        <input type="text" class="form-control fw-semibold" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn (SEO)</label>
                        <textarea class="form-control" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    {{-- Giữ lại trường description cũ nếu cần, nếu không có thể ẩn đi --}}
                    <div class="mb-3">
                        <label class="form-label">Mô tả (Cơ bản)</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Chi tiết dài (Bài viết)</label>
                        <textarea class="form-control" name="long_description" rows="8">{{ old('long_description', $product->long_description) }}</textarea>
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
                            <textarea class="form-control font-monospace text-muted" name="specs" rows="6">{{ old('specs', $product->specs) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hướng dẫn chăm sóc</label>
                            <textarea class="form-control" name="care_guide" rows="6">{{ old('care_guide', $product->care_guide) }}</textarea>
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
                            <input type="number" step="0.01" max="99999999.99" class="form-control fw-bold text-success" name="price" value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text bg-light">₫</span>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label required">Số lượng tồn kho</label>
                        <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                        <div class="form-text mt-1">
                            @if($product->quantity == 0) <span class="text-danger">Đang hết hàng</span>
                            @else <span class="text-success">Đang có hàng</span> @endif
                        </div>
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
                                <option value="{{ $c->id }}" @if(old('category_id', $product->category_id) == $c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Thương hiệu</label>
                        <select class="form-select" name="brand_id">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" @if(old('brand_id', $product->brand_id) == $b->id) selected @endif>{{ $b->name }}</option>
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
                    <div class="mb-3 text-center">
                        <label class="form-label d-block text-start">Ảnh hiện tại</label>
                        <div class="current-img-container">
                            <div class="current-img-badge">Current</div>
                            <img id="currentImageDisplay" 
                                 src="{{ asset('assets/img/products/'.$product->image) }}" 
                                 class="img-fluid" 
                                 style="max-height: 150px; object-fit: contain;"
                                 onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                        </div>
                    </div>

                    <div class="image-upload-zone mb-3">
                        <div class="text-center">
                            <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                            <p class="mb-0 small fw-semibold text-secondary mt-1">Tải ảnh mới để thay thế</p>
                        </div>
                        <input type="file" name="image_file" accept="image/*" onchange="previewImage(this)">
                    </div>

                    {{-- Preview Area (Hidden by default) --}}
                    <div id="newImagePreviewContainer" class="text-center d-none mb-3 bg-light p-2 rounded">
                        <small class="d-block text-success mb-1 fw-bold">Ảnh mới sẽ dùng:</small>
                        <img id="newImagePreview" src="" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                    </div>

                    {{-- Tên file text (Legacy) --}}
                    <div class="border-top pt-3 mt-3">
                        <label class="form-label small text-muted">Tên file ảnh (Thủ công):</label>
                        <input class="form-control form-control-sm text-secondary" name="image" value="{{ old('image', $product->image) }}">
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        const container = document.getElementById('newImagePreviewContainer');
        const img = document.getElementById('newImagePreview');
        const currentImg = document.getElementById('currentImageDisplay');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('d-none');
                
                // Optional: Làm mờ ảnh cũ để user biết nó sẽ bị thay thế
                if(currentImg) currentImg.style.opacity = '0.5';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection