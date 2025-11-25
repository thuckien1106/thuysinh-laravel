@extends('layouts.admin')
@section('title','Quản lý sản phẩm')
@section('content')

<style>
    /* ================= PRODUCT LIST THEME ================= */
    :root {
        --table-header-bg: #f8fafc;
        --border-color: #f1f5f9;
        --primary-color: #0f172a;
    }

    /* Thanh Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    /* Bộ lọc (Filter Bar) */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
    }
    .search-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    .search-input:focus {
        background-color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Bảng sản phẩm */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .product-table th {
        background-color: var(--table-header-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .product-table td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
    }
    
    .product-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Cột thông tin sản phẩm (Ảnh + Tên) */
    .product-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .product-img-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
        flex-shrink: 0;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-meta h6 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    .product-meta small {
        color: #64748b;
        font-size: 0.8rem;
    }

    /* Giá bán */
    .price-tag {
        font-weight: 700;
        color: #059669; /* Xanh lá đậm */
        font-family: 'Nunito', sans-serif;
    }

    /* Badge tồn kho */
    .stock-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .stock-in { background-color: #d1fae5; color: #065f46; }
    .stock-low { background-color: #fef3c7; color: #92400e; }
    .stock-out { background-color: #fee2e2; color: #b91c1c; }

    /* Nút hành động */
    .action-btn {
        width: 36px;
        height: 36px;
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

    /* Nút thêm mới */
    .btn-create {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        transition: transform 0.2s;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Sản phẩm</h3>
        <small class="text-muted">Quản lý danh sách và kho hàng</small>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-create text-decoration-none">
        <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm
    </a>
</div>

{{-- FILTER SECTION --}}
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-lg-4">
            <label class="form-label small fw-bold text-muted mb-1">Tìm kiếm</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="q" value="{{ $q }}" class="form-control search-input border-start-0 ps-0" placeholder="Tên sản phẩm...">
            </div>
        </div>
        <div class="col-lg-2">
            <label class="form-label small fw-bold text-muted mb-1">Danh mục</label>
            <select class="form-select search-input" name="category">
                <option value="">Tất cả</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @if($category==$c->id) selected @endif>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label small fw-bold text-muted mb-1">Thương hiệu</label>
            <select class="form-select search-input" name="brand">
                <option value="">Tất cả</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" @if($brand==$b->id) selected @endif>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label class="form-label small fw-bold text-muted mb-1">Sắp xếp</label>
            <select class="form-select search-input" name="sort">
                <option value="created_desc" @if($sort==='created_desc') selected @endif>Mới nhất</option>
                <option value="price_asc" @if($sort==='price_asc') selected @endif>Giá thấp → cao</option>
                <option value="price_desc" @if($sort==='price_desc') selected @endif>Giá cao → thấp</option>
                <option value="qty_asc" @if($sort==='qty_asc') selected @endif>Tồn kho ít nhất</option>
            </select>
        </div>
        <div class="col-lg-2 d-flex gap-2">
            <button class="btn btn-primary w-100 fw-bold rounded-3">Lọc</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-light border w-auto rounded-3" title="Xóa lọc">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

{{-- TABLE SECTION --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table product-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Sản phẩm</th>
                    <th class="text-end">Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Thương hiệu</th>
                    <th class="text-end pe-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    {{-- Cột Sản phẩm: Ảnh + Tên + Danh mục --}}
                    <td class="ps-4">
                        <div class="product-info">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('assets/img/products/'.$p->image) }}" 
                                     class="product-img" 
                                     alt="{{ $p->name }}"
                                     onerror="this.src='https://placehold.co/100x100?text=No+Img'">
                            </div>
                            <div class="product-meta">
                                <h6>{{ \Illuminate\Support\Str::limit($p->name, 40) }}</h6>
                                <small>ID: #{{ $p->id }} • {{ $p->category->name ?? 'Không phân loại' }}</small>
                            </div>
                        </div>
                    </td>

                    {{-- Cột Giá --}}
                    <td class="text-end">
                        <div class="price-tag">{{ number_format($p->price,0,',','.') }} đ</div>
                    </td>

                    {{-- Cột Tồn kho --}}
                    <td>
                        @if($p->quantity == 0)
                            <span class="stock-badge stock-out">
                                <i class="bi bi-x-circle-fill"></i> Hết hàng
                            </span>
                        @elseif($p->quantity <= 5)
                            <span class="stock-badge stock-low">
                                <i class="bi bi-exclamation-circle-fill"></i> Còn {{ $p->quantity }}
                            </span>
                        @else
                            <span class="stock-badge stock-in">
                                <i class="bi bi-check-circle-fill"></i> Sẵn hàng ({{ $p->quantity }})
                            </span>
                        @endif
                    </td>

                    {{-- Cột Thương hiệu --}}
                    <td>
                        <span class="text-dark small fw-semibold">{{ $p->brand->name ?? '-' }}</span>
                    </td>

                    {{-- Cột Hành động --}}
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.products.edit',$p->id) }}" class="action-btn" title="Chỉnh sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy',$p->id) }}" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" style="display:inline;">
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
                    <td colspan="5" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 opacity-50"></i>
                            <h6 class="text-muted fw-bold">Không tìm thấy sản phẩm nào</h6>
                            <p class="text-muted small mb-0">Thử thay đổi bộ lọc hoặc thêm sản phẩm mới.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $products->withQueryString()->links() }}
</div>

@endsection
