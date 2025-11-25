@extends('layouts.admin')
@section('title','Chi tiết đơn hàng #' . $order->id)
@section('content')

<style>
    /* ================= ORDER DETAIL THEME ================= */
    :root {
        --card-radius: 12px;
        --border-color: #f1f5f9;
        --primary-color: #3b82f6;
    }

    /* Card Styling */
    .detail-card {
        background: #fff;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .detail-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .detail-card-title {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }
    .detail-card-body {
        padding: 20px;
    }

    /* Item Table */
    .item-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        border-top: none;
    }
    .item-table td {
        vertical-align: middle;
        padding: 16px;
    }
    .product-thumb {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    /* Info Row */
    .info-row {
        display: flex;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    .info-label {
        width: 120px;
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }
    .info-value {
        color: #0f172a;
        font-weight: 600;
    }

    /* Status Badge in Header */
    .header-badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            Đơn hàng #{{ $order->id }}
        </h3>
        <div class="text-muted small">
            <i class="bi bi-clock me-1"></i> Ngày đặt: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
        </div>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary px-3" style="border-radius: 10px;">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="alert alert-success rounded-3 shadow-sm border-0 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
    </div>
@endif

<div class="row g-4">
    {{-- CỘT TRÁI: DANH SÁCH & CHI TIẾT --}}
    <div class="col-lg-8">
        
        <!-- 1. Danh sách sản phẩm -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="bi bi-bag-check me-2 text-primary"></i> Chi tiết đơn hàng
                </h5>
                <span class="badge bg-light text-dark border">{{ count($items) }} sản phẩm</span>
            </div>
            <div class="table-responsive">
                <table class="table item-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">SL</th>
                            <th class="text-end pe-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('assets/img/products/'.($it->product_image ?? 'default.png')) }}" 
                                         alt="Img" class="product-thumb"
                                         onerror="this.src='https://placehold.co/50x50?text=NoImg'">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $it->product_name ?? 'Sản phẩm #'.$it->product_id }}</div>
                                        @if($it->variant && $it->variant !== 'Tiêu chuẩn')
                                            <span class="badge bg-light text-secondary border fw-normal">{{ $it->variant }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-muted">{{ number_format($it->price,0,',','.') }}</td>
                            <td class="text-center fw-semibold">x{{ $it->quantity }}</td>
                            <td class="text-end pe-4 fw-bold text-dark">{{ number_format($it->price * $it->quantity,0,',','.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 2px solid #f1f5f9;">
                        <tr>
                            <td colspan="3" class="text-end py-3 text-muted small text-uppercase fw-bold">Tổng giá trị đơn hàng</td>
                            <td class="text-end py-3 pe-4">
                                <span class="fs-5 fw-bold text-primary">{{ number_format($order->total,0,',','.') }} đ</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- 2. Thông tin thanh toán & vận chuyển -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="bi bi-credit-card-2-front me-2 text-info"></i> Thanh toán & Vận chuyển
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Thanh toán</h6>
                        @php
                            $payMethodMap = ['cod'=>'Thanh toán khi nhận hàng (COD)', 'online'=>'Chuyển khoản / Online'];
                            $payStatusMap = ['pending'=>'Chờ xử lý', 'paid'=>'Đã thanh toán', 'failed'=>'Thất bại'];
                            $payStatusClass = match($payment->status ?? 'pending') {
                                'paid' => 'text-success',
                                'failed' => 'text-danger',
                                default => 'text-warning'
                            };
                        @endphp
                        <div class="mb-2">
                            <span class="d-block text-muted small">Phương thức:</span>
                            <span class="fw-semibold">{{ $payMethodMap[$payment->method ?? 'cod'] ?? ($payment->method ?? 'cod') }}</span>
                        </div>
                        <div>
                            <span class="d-block text-muted small">Trạng thái:</span>
                            <span class="fw-bold {{ $payStatusClass }}">
                                <i class="bi bi-circle-fill small me-1" style="font-size: 8px;"></i>
                                {{ $payStatusMap[$payment->status ?? 'pending'] ?? ($payment->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Vận chuyển</h6>
                        @php
                            $shipStatusMap = ['pending'=>'Chờ lấy hàng', 'shipping'=>'Đang giao hàng', 'delivered'=>'Giao thành công', 'cancelled'=>'Đã hủy'];
                            $shipStatusClass = match($shipment->status ?? 'pending') {
                                'delivered' => 'text-success',
                                'cancelled' => 'text-danger',
                                'shipping' => 'text-primary',
                                default => 'text-secondary'
                            };
                        @endphp
                        <div class="mb-2">
                            <span class="d-block text-muted small">Đơn vị vận chuyển:</span>
                            <span class="fw-semibold">{{ $shipment->carrier ?? 'Nội bộ cửa hàng' }}</span>
                        </div>
                        <div>
                            <span class="d-block text-muted small">Trạng thái giao hàng:</span>
                            <span class="fw-bold {{ $shipStatusClass }}">
                                {{ $shipStatusMap[$shipment->status ?? 'pending'] ?? ($shipment->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CỘT PHẢI: TRẠNG THÁI & KHÁCH HÀNG --}}
    <div class="col-lg-4">
        
        <!-- 3. Cập nhật trạng thái -->
        <div class="detail-card border-primary border-opacity-25" style="background: #f8fbff;">
            <div class="detail-card-body">
                <h6 class="fw-bold text-primary mb-3">Cập nhật trạng thái đơn</h6>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select border-primary border-opacity-25" name="status">
                            @foreach(\App\Models\Order::STATUS_OPTIONS as $code => $label)
                                <option value="{{ $code }}" @if($order->getRawOriginal('status') === $code) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary w-100 fw-bold" style="border-radius: 8px;">
                        <i class="bi bi-save me-1"></i> Lưu trạng thái
                    </button>
                </form>
            </div>
        </div>

        <!-- 4. Thông tin khách hàng -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">
                    <i class="bi bi-person-badge me-2 text-secondary"></i> Khách hàng
                </h5>
            </div>
            <div class="detail-card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold fs-4 me-3" style="width: 50px; height: 50px;">
                        {{ substr($order->customer_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold fs-6">{{ $order->customer_name }}</div>
                        <div class="text-muted small">Khách hàng</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">Liên hệ</label>
                    <div class="d-flex align-items-center text-dark">
                        <i class="bi bi-telephone me-2 text-secondary"></i>
                        {{ $order->phone ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div>
                    <label class="small text-muted fw-bold text-uppercase mb-1">Địa chỉ giao hàng</label>
                    <div class="d-flex align-items-start text-dark">
                        <i class="bi bi-geo-alt me-2 mt-1 text-danger"></i>
                        <span>{{ $order->customer_address }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection