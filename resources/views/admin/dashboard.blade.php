@extends('layouts.admin')
@section('title','Bảng điều khiển')
@section('content')

<style>
    /* ================= DASHBOARD THEME ================= */
    :root {
        --card-radius: 16px;
        --card-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
        --text-primary: #2c3e50;
        --text-secondary: #95a5a6;
        --bg-hover: #f8f9fa;
    }

    /* Tiêu đề & Header */
    .admin-headerbar {
        background: transparent;
        padding-bottom: 20px;
        border-bottom: 1px dashed #e0e0e0;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .admin-headerbar h3 {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1.5rem;
        margin: 0;
    }

    /* Các thẻ thống kê (Stat Cards) */
    .stat-card {
        background: #fff;
        border-radius: var(--card-radius);
        padding: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid #f0f2f5;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }
    
    .stat-card .label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    
    .stat-card .value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    /* Icon Wrapper */
    .icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    
    /* Màu sắc theme cho cards */
    .theme-blue .icon-wrapper { background-color: #e3f2fd; color: #1565c0; }
    .theme-green .icon-wrapper { background-color: #e8f5e9; color: #2e7d32; }
    .theme-orange .icon-wrapper { background-color: #fff3e0; color: #ef6c00; }
    .theme-purple .icon-wrapper { background-color: #f3e5f5; color: #7b1fa2; }

    .stat-content-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    /* Container chung cho Dashboard Cards */
    .dashboard-card {
        background: #fff;
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Bo tròn góc cho con */
    }
    .dashboard-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f2f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
    }
    .dashboard-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }
    .dashboard-card-body {
        padding: 24px;
        flex: 1;
    }

    /* Tùy chỉnh Table */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background-color: #f9fafb;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.8rem;
        text-transform: uppercase;
        border-top: none;
        padding: 12px 16px;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 1px solid #edf2f7;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 14px 16px;
        font-size: 0.95rem;
        color: var(--text-primary);
        border-bottom: 1px solid #f1f5f9;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .table-custom tr:hover td {
        background-color: var(--bg-hover);
        cursor: pointer;
    }

    /* Badge trạng thái */
    .badge-soft {
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-block;
    }
    .badge-soft-success { background-color: #d1fae5; color: #065f46; }
    .badge-soft-warning { background-color: #fef3c7; color: #92400e; }
    .badge-soft-danger  { background-color: #fee2e2; color: #b91c1c; }
    .badge-soft-info    { background-color: #dbeafe; color: #1e40af; }

    /* Nút thêm mới */
    .btn-create-new {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-create-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        color: white;
    }

    /* === THANH CUỘN TÙY CHỈNH (SCROLLBAR) === */
    .custom-scroll {
        max-height: 420px; /* Chiều cao cố định để không bị tràn layout */
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
</style>

<div class="admin-headerbar">
    <div>
        <h3>Tổng quan</h3>
        <small class="text-muted">Chào mừng trở lại! Đây là tình hình kinh doanh hôm nay.</small>
    </div>
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-create-new">
            <i class="bi bi-plus-lg me-2"></i> Thêm sản phẩm
        </a>
    </div>
</div>

{{-- SECTION 1: CÁC CHỈ SỐ QUAN TRỌNG --}}
<div class="row g-4 mb-4">
    <!-- Doanh thu -->
    <div class="col-md-3">
        <div class="stat-card theme-green">
            <div class="stat-content-wrapper">
                <div>
                    <div class="label">Tổng doanh thu</div>
                    <div class="value text-success">{{ number_format($stats['revenue'],0,',','.') }} <span class="fs-6">đ</span></div>
                    <div class="mt-2 small text-secondary">
                        Hôm nay: <strong class="text-dark">{{ number_format($stats['revenue_today'],0,',','.') }} đ</strong>
                    </div>
                </div>
                <div class="icon-wrapper">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng -->
    <div class="col-md-3">
        <div class="stat-card theme-blue">
            <div class="stat-content-wrapper">
                <div>
                    <div class="label">Tổng đơn hàng</div>
                    <div class="value">{{ number_format($stats['orders']) }}</div>
                    <div class="mt-2 small text-secondary">
                        Hôm nay: <strong class="text-dark">{{ number_format($stats['orders_today']) }}</strong> đơn
                    </div>
                </div>
                <div class="icon-wrapper">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm -->
    <div class="col-md-3">
        <div class="stat-card theme-purple">
            <div class="stat-content-wrapper">
                <div>
                    <div class="label">Sản phẩm</div>
                    <div class="value">{{ number_format($stats['products']) }}</div>
                    <div class="mt-2 small text-secondary">
                        Đang hoạt động
                    </div>
                </div>
                <div class="icon-wrapper">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Khách hàng -->
    <div class="col-md-3">
        <div class="stat-card theme-orange">
            <div class="stat-content-wrapper">
                <div>
                    <div class="label">Khách hàng</div>
                    <div class="value">{{ number_format($stats['users']) }}</div>
                    <div class="mt-2 small text-secondary">
                        Tài khoản đăng ký
                    </div>
                </div>
                <div class="icon-wrapper">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: TRẠNG THÁI ĐƠN HÀNG NHANH --}}
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="d-block text-secondary small fw-bold text-uppercase">Đang xử lý</span>
                <span class="fs-4 fw-bold text-primary">{{ number_format($stats['processing']) }}</span>
            </div>
            <div class="bg-blue-50 p-2 rounded">
                <i class="bi bi-hourglass-split fs-4 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <span class="d-block text-secondary small fw-bold text-uppercase">Hoàn thành</span>
                <span class="fs-4 fw-bold text-success">{{ number_format($stats['completed']) }}</span>
            </div>
            <div class="bg-green-50 p-2 rounded">
                <i class="bi bi-check-circle fs-4 text-success"></i>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 3: BIỂU ĐỒ VÀ BẢNG DỮ LIỆU --}}
<div class="row g-4 mb-4">
    <!-- CỘT TRÁI: BIỂU ĐỒ DOANH THU & TOP SẢN PHẨM -->
    <div class="col-lg-8">
        <!-- 1. Biểu đồ doanh thu -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Doanh thu 7 ngày qua</h5>
            </div>
            <div class="dashboard-card-body">
                <canvas id="rev7" height="110"></canvas>
            </div>
        </div>

        <!-- 2. Top sản phẩm (Có cuộn) -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5><i class="bi bi-trophy me-2 text-warning"></i>Top sản phẩm bán chạy</h5>
            </div>
            <div class="dashboard-card-body p-0 custom-scroll">
                <table class="table table-custom w-100">
                    <thead>
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end pe-4">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $tp)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">{{ $tp->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $tp->qty }}</span>
                            </td>
                            <td class="text-end pe-4 text-success fw-bold">
                                {{ number_format($tp->amount,0,',','.') }} đ
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CỘT PHẢI: BIỂU ĐỒ TRÒN & BIỂU ĐỒ DANH MỤC -->
    <div class="col-lg-4">
        <!-- 3. Biểu đồ trạng thái -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5><i class="bi bi-pie-chart me-2 text-info"></i>Tỷ lệ đơn hàng</h5>
            </div>
            <div class="dashboard-card-body">
                <div style="position: relative; height: 220px; width: 100%;">
                    <canvas id="orderStatus"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Biểu đồ danh mục (Bar Chart) -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5><i class="bi bi-tags me-2 text-secondary"></i>Theo danh mục</h5>
            </div>
            <div class="dashboard-card-body">
                 <canvas id="cateSales" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 4: DANH SÁCH ĐƠN HÀNG MỚI NHẤT (Nằm ngang - Full Width) --}}
<div class="row">
    <div class="col-12">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5><i class="bi bi-clock-history me-2 text-secondary"></i>Đơn hàng mới nhất</h5>
            </div>
            <div class="dashboard-card-body p-0 custom-scroll">
                <table class="table table-custom w-100 table-hover">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 10%">Mã đơn</th>
                            <th style="width: 20%">Khách hàng</th>
                            <th style="width: 30%">Sản phẩm</th> {{-- Cột mới --}}
                            <th style="width: 15%">Ngày đặt</th>
                            <th style="width: 10%">Trạng thái</th>
                            <th class="text-end pe-4" style="width: 15%">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $o)
                        <tr onclick="window.location='{{ route('admin.orders.show', $o->id) }}'">
                            <td class="ps-4 fw-bold text-primary">#{{ $o->id }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $o->customer_name }}</div>
                            </td>
                            <td>
                                {{-- Hiển thị tên sản phẩm --}}
                                @php
                                    $items = $o->items ?? collect([]);
                                    $firstItem = $items->first();
                                    $moreCount = $items->count() - 1;
                                @endphp
                                @if($firstItem)
                                    <span class="text-dark">{{ $firstItem->product_name ?? 'Sản phẩm...' }}</span>
                                    @if($moreCount > 0)
                                        <span class="badge bg-light text-secondary border ms-1">+{{ $moreCount }}</span>
                                    @endif
                                @else
                                    <span class="text-muted small fst-italic">Chi tiết xem trong đơn</span>
                                @endif
                            </td>
                            <td class="text-secondary small">
                                {{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @php
                                    $statusClass = match(strtolower($o->status)) {
                                        'completed' => 'badge-soft-success',
                                        'pending', 'processing' => 'badge-soft-info',
                                        'cancelled' => 'badge-soft-danger',
                                        default => 'badge-soft-warning'
                                    };
                                    $statusText = ucfirst($o->status);
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="text-end pe-4 fw-bold text-dark">
                                {{ number_format($o->total,0,',','.') }} đ
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Chưa có đơn hàng nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Config font & color default
    Chart.defaults.font.family = "'Nunito', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';
    
    // 1. CHART DOANH THU (Line)
    const revCtx = document.getElementById('rev7').getContext('2d');
    const gradient = revCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: @json($chart['labels']),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chart['data']),
                borderColor: '#10b981', // Emerald 500
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Thêm dòng này để chart tự co giãn tốt hơn
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: (ctx) => ' ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4], color: '#f1f5f9' },
                    ticks: { callback: (val) => val.toLocaleString('vi-VN') + ' đ' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. CHART TRẠNG THÁI (Doughnut)
    new Chart(document.getElementById('orderStatus'), {
        type: 'doughnut',
        data: {
            labels: @json(array_values($statusLabels)),
            datasets: [{
                data: @json(array_values($statusCounts)),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'], // Blue, Green, Yellow, Red
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, boxWidth: 8 } }
            }
        }
    });

    // 3. CHART DANH MỤC (Bar)
    new Chart(document.getElementById('cateSales'), {
        type: 'bar',
        data: {
            labels: @json(collect($categorySales)->pluck('name')),
            datasets: [{
                label: 'Doanh thu',
                data: @json(collect($categorySales)->pluck('amount')),
                backgroundColor: '#6366f1', // Indigo 500
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Thêm dòng này
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { display: false } }, // Ẩn số trục Y cho gọn
                x: { grid: { display: false } }
            }
        }
    });
</script>

@endsection