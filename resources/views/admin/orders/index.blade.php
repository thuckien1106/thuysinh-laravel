@extends('layouts.admin')
@section('title','Quản lý đơn hàng')
@section('content')

<style>
    /* ================= ORDER LIST THEME ================= */
    :root {
        --table-bg: #fff;
        --table-hover: #f8fafc;
        --border-color: #e2e8f0;
        --primary-color: #3b82f6;
    }

    /* Header Page */
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
    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
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

    /* Table Styling */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .custom-table th {
        background-color: #f1f5f9;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #475569;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
    }
    .custom-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
    }
    .custom-table tr:hover td {
        background-color: var(--table-hover);
    }
    .custom-table tr:last-child td {
        border-bottom: none;
    }

    /* Status Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    /* Định nghĩa màu dựa trên text */
    .status-completed { background-color: #dcfce7; color: #166534; } /* Xanh lá */
    .status-shipping  { background-color: #e0f2fe; color: #0369a1; } /* Xanh dương */
    .status-processing{ background-color: #ffedd5; color: #c2410c; } /* Cam */
    .status-cancelled { background-color: #f1f5f9; color: #475569; } /* Xám */
    .status-default   { background-color: #f3f4f6; color: #374151; }

    /* Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        background-color: #eff6ff;
        color: var(--primary-color);
    }
    .btn-action:hover {
        background-color: var(--primary-color);
        color: white;
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Quản lý đơn hàng</h3>
        <small class="text-muted">Theo dõi và xử lý các đơn đặt hàng</small>
    </div>
    <a href="{{ route('admin.orders.export.csv', request()->all()) }}" class="btn btn-success fw-bold text-white px-3" style="border-radius: 10px;">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Xuất CSV
    </a>
</div>

{{-- FILTER SECTION --}}
<div class="filter-card">
    <form class="row g-3" method="GET">
        <!-- Tìm kiếm -->
        <div class="col-lg-3">
            <span class="filter-label">Tìm kiếm</span>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input class="form-control search-input border-start-0 ps-0" type="text" name="q" value="{{ $q }}" placeholder="Mã đơn / Tên khách...">
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="col-lg-2">
            <span class="filter-label">Trạng thái</span>
            <select class="form-select search-input" name="status">
                <option value="">-- Tất cả --</option>
                @foreach(['Đang xử lý','Đang giao','Hoàn thành','Đã hủy'] as $st)
                    <option value="{{ $st }}" @if($status==$st) selected @endif>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <!-- Thời gian -->
        <div class="col-lg-3">
            <span class="filter-label">Thời gian đặt hàng</span>
            <div class="input-group">
                <input class="form-control search-input" type="date" name="from" value="{{ $from }}">
                <span class="input-group-text bg-light border-start-0 border-end-0 small text-muted">đến</span>
                <input class="form-control search-input" type="date" name="to" value="{{ $to }}">
            </div>
        </div>

        <!-- Sắp xếp -->
        <div class="col-lg-2">
            <span class="filter-label">Sắp xếp</span>
            <select class="form-select search-input" name="sort">
                <option value="">Mặc định</option>
                <option value="date_asc" @if($sort==='date_asc') selected @endif>Ngày cũ nhất</option>
                <option value="total_desc" @if($sort==='total_desc') selected @endif>Giá trị cao nhất</option>
                <option value="total_asc" @if($sort==='total_asc') selected @endif>Giá trị thấp nhất</option>
            </select>
        </div>

        <!-- Nút Lọc -->
        <div class="col-lg-2 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-100 fw-bold" style="border-radius: 8px;">Lọc</button>
            <a class="btn btn-light border w-auto" style="border-radius: 8px;" href="{{ route('admin.orders.index') }}" title="Xóa lọc">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

{{-- TABLE SECTION --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table custom-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Khách hàng</th>
                    <th class="text-end">Tổng tiền</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-end pe-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td class="ps-4 fw-bold text-primary">#{{ $o->id }}</td>
                    
                    <td class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y H:i') }}
                    </td>
                    
                    <td class="fw-semibold text-dark">{{ $o->customer_name }}</td>
                    
                    <td class="text-end fw-bold text-dark">
                        {{ number_format($o->total, 0, ',', '.') }} đ
                    </td>
                    
                    <td class="text-center">
                        @php
                            $statusClass = match($o->status) {
                                'Hoàn thành' => 'status-completed',
                                'Đang giao' => 'status-shipping',
                                'Đang xử lý', 'Chờ xử lý' => 'status-processing',
                                'Đã hủy' => 'status-cancelled',
                                default => 'status-default'
                            };
                        @endphp
                        <span class="badge-status {{ $statusClass }}">
                            <i class="bi bi-dot"></i> {{ $o->status }}
                        </span>
                    </td>
                    
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end">
                            <a class="btn-action" href="{{ route('admin.orders.show',$o->id) }}" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 opacity-50"></i>
                            <h6 class="text-muted fw-bold">Không tìm thấy đơn hàng nào</h6>
                            <p class="text-muted small mb-0">Thử thay đổi bộ lọc tìm kiếm.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $orders->links() }}
</div>

@endsection
