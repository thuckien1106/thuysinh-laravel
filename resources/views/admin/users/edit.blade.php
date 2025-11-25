@extends('layouts.admin')
@section('title','Sửa người dùng')

@section('content')
<style>
    /* UI Customization */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .form-label {
        font-weight: 600;
        color: #344767;
        font-size: 0.875rem;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
        color: #6c757d;
    }
    .form-control, .form-select {
        border-left: none;
        padding-left: 0;
        box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #ced4da;
    }
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border-radius: 0.375rem;
        border: 1px solid #86b7fe;
    }
    .input-group:focus-within .input-group-text {
        border-color: #86b7fe;
    }
    .input-group:focus-within .form-control, 
    .input-group:focus-within .form-select {
        border-color: #86b7fe;
    }
    /* Viền giả cho input group để tạo cảm giác liền mạch */
    .input-group-custom {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .input-group-custom .input-group-text {
        border: none;
    }
    .input-group-custom .form-control,
    .input-group-custom .form-select {
        border: none;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Cập nhật tài khoản</h3>
            <span class="text-muted small">Chỉnh sửa thông tin thành viên ID: <strong>#{{ $user->id }}</strong></span>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <h6 class="text-uppercase text-secondary fw-bold small mb-3"><i class="bi bi-person-badge me-2"></i>Thông tin cơ bản</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Nhập tên người dùng">
                                </div>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Địa chỉ Email <span class="text-danger">*</span></label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="name@example.com">
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="role" class="form-label">Vai trò (Quyền hạn)</label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                        <option value="user" {{ ($user->role ?? 'user') === 'user' ? 'selected' : '' }}>Thành viên (User)</option>
                                        <option value="admin" {{ ($user->role ?? 'user') === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                                    </select>
                                </div>
                                @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <h6 class="text-uppercase text-secondary fw-bold small mb-3"><i class="bi bi-key me-2"></i>Bảo mật</h6>
                        <div class="alert alert-light border border-secondary-subtle d-flex align-items-center mb-3" role="alert">
                            <i class="bi bi-info-circle-fill text-info me-2 fs-5"></i>
                            <div class="small text-muted">Chỉ nhập vào các ô bên dưới nếu bạn muốn thay đổi mật khẩu. Nếu không, hãy để trống.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••">
                                </div>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <div class="input-group input-group-custom">
                                    <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                <i class="bi bi-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection