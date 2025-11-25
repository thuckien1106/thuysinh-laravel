@extends('layouts.header')
@section('title', 'Đơn hàng của tôi')
@section('content')

<style>
    /* ================= MY ORDER BLUE THEME ================= */
    :root {
        --primary-color: #007bff;       /* Blue standard matching header */
        --primary-hover: #0056b3;       
        --bg-surface: #ffffff;
        --text-main: #0f172a;
        --text-light: #64748b;
    }

    /* Page Title */
    .page-title {
        font-weight: 800;
        color: var(--text-main);
        background: linear-gradient(135deg, #0d47a1 0%, #007bff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0;
    }

    /* Tabs - Modern Pills */
    .nav-pills {
        gap: 8px;
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 5px; /* For scrollbar spacing */
    }
    .nav-pills .nav-link {
        color: var(--text-light);
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 50px;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        background: transparent;
        border: 1px solid #f1f5f9;
        white-space: nowrap;
    }
    .nav-pills .nav-link:hover {
        color: var(--primary-color);
        background-color: #eff6ff;
        border-color: #bfdbfe;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #007bff, #0062cc);
        color: white !important;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        border-color: transparent;
    }

    /* Card Container */
    .order-card {
        background: var(--bg-surface);
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        animation: fadeUp 0.5s ease;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Table Styling */
    .custom-table th {
        background-color: #f8fafc;
        font-weight: 700;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .custom-table td {
        padding: 18px 24px;
        vertical-align: middle;
        color: var(--text-main);
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    .custom-table tr:last-child td {
        border-bottom: none;
    }
    .custom-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }
    /* Status Colors */
    .bg-status-processing { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; } /* Orange */
    .bg-status-shipping   { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; } /* Blue */
    .bg-status-completed  { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; } /* Green */
    .bg-status-cancelled  { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; } /* Red */

    /* Mini Badges (Pay/Ship) */
    .mini-badge {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .mini-badge.success { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .mini-badge.warning { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .mini-badge.danger  { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .mini-badge.primary { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }

    /* Buttons */
    .btn-action {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-detail {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .btn-detail:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }
    .btn-cancel {
        background-color: #fff;
        color: #dc2626;
        border: 1px solid #fee2e2;
    }
    .btn-cancel:hover {
        background-color: #fef2f2;
        border-color: #fecaca;
    }
    .btn-receive {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
    .btn-receive:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }
    .empty-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
        display: block;
    }
</style>

<div class="container py-5">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-5 gap-3">
        <div>
            <h2 class="page-title display-6">Đơn hàng của tôi</h2>
            <p class="text-muted mb-0">Theo dõi và quản lý lịch sử mua sắm của bạn</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>

    @php $tab = \App\Models\Order::normalizeStatus($statusParam ?? null) ?: 'all'; @endphp

    <!-- TABS -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item"><a class="nav-link {{ $tab==='all' ? 'active' : '' }}" href="{{ route('orders.mine') }}">Tất cả</a></li>
        <li class="nav-item"><a class="nav-link {{ $tab==='processing' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'processing']) }}">Đang xử lý</a></li>
        <li class="nav-item"><a class="nav-link {{ $tab==='shipping' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'shipping']) }}">Đang giao</a></li>
        <li class="nav-item"><a class="nav-link {{ $tab==='completed' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'completed']) }}">Đã nhận</a></li>
        <li class="nav-item"><a class="nav-link {{ $tab==='cancelled' ? 'active' : '' }}" href="{{ route('orders.mine',['status'=>'cancelled']) }}">Đã hủy</a></li>
    </ul>

    <!-- ORDER LIST -->
    <div class="order-card">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th class="text-end">Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-center">Vận chuyển</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        @php
                            $p = optional($payments->get($o->id))->last();
                            $s = optional($shipments->get($o->id))->last();
                            $statusCode = \App\Models\Order::normalizeStatus($o->getRawOriginal('status') ?? $o->status);

                            // Mapping Classes
                            $statusClass = match($statusCode) {
                                'processing' => 'bg-status-processing',
                                'shipping' => 'bg-status-shipping',
                                'completed' => 'bg-status-completed',
                                'cancelled' => 'bg-status-cancelled',
                                default => 'bg-light text-dark border'
                            };
                            
                            $statusIcon = match($statusCode) {
                                'processing' => 'bi-hourglass-split',
                                'shipping' => 'bi-truck',
                                'completed' => 'bi-check-circle-fill',
                                'cancelled' => 'bi-x-circle-fill',
                                default => 'bi-circle'
                            };

                            $payStatus = $p->status ?? 'pending';
                            $shipStatus = $s->status ?? 'pending';
                            
                            $payBadgeClass = match($payStatus) { 'paid' => 'success', 'failed' => 'danger', default => 'warning' };
                            $shipBadgeClass = match($shipStatus) { 'delivered' => 'success', 'cancelled' => 'danger', 'shipping' => 'primary', default => 'warning' }; 
                        @endphp

                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">#{{ $o->id }}</span>
                            </td>
                            <td class="text-muted fw-medium">
                                {{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y') }} 
                                <div class="small text-secondary opacity-75">{{ \Carbon\Carbon::parse($o->created_at)->format('H:i') }}</div>
                            </td>
                            <td class="text-end fw-bold text-dark fs-6">
                                {{ number_format($o->total) }} ₫
                            </td>
                            <td class="text-center">
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }}"></i> {{ ucfirst($o->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="mini-badge {{ $payBadgeClass }}">
                                    {{ $payStatus === 'paid' ? 'Đã TT' : ($payStatus === 'failed' ? 'Lỗi' : 'Chờ TT') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="mini-badge {{ $shipBadgeClass }}">
                                    {{ 
                                        $shipStatus === 'shipping' ? 'Đang giao' : 
                                        ($shipStatus === 'delivered' ? 'Đã giao' : 
                                        ($shipStatus === 'cancelled' ? 'Đã hủy' : 'Chờ giao')) 
                                    }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('order.thankyou',$o->id) }}" class="btn btn-action btn-detail">
                                        Chi tiết
                                    </a>

                                    @if($statusCode === 'processing')
                                        <form method="POST" action="{{ route('orders.cancel',$o->id) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng #{{ $o->id }}?')">
                                            @csrf
                                            <button class="btn btn-action btn-cancel">Hủy</button>
                                        </form>
                                    @endif

                                    @if($statusCode === 'shipping')
                                        <form method="POST" action="{{ route('orders.received',$o->id) }}" onsubmit="return confirm('Xác nhận đã nhận được hàng?')">
                                            @csrf
                                            <button class="btn btn-action btn-receive">
                                                <i class="bi bi-check2-circle"></i> Đã nhận
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-box-seam empty-icon"></i>
                                    <h5 class="fw-bold text-secondary">Chưa có đơn hàng nào</h5>
                                    <p class="text-muted small mb-4">Hãy dạo qua cửa hàng và chọn cho mình những sản phẩm ưng ý nhé!</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                                        <i class="bi bi-cart-plus me-2"></i>Mua sắm ngay
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection