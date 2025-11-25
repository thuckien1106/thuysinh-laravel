@extends('layouts.admin')
@section('title','Quản lý người dùng')

@section('content')

<style>
    /* Custom CSS cho giao diện Admin */
    :root {
        --primary-soft: #e0f2f1;
        --primary-color: #009688;
    }
    
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-custom thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        border-bottom: 2px solid #edf2f9;
    }

    .table-custom tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        color: #344767;
        border-bottom: 1px solid #f1f1f4;
    }

    .avatar-initial {
        width: 40px;
        height: 40px;
        background-color: var(--primary-soft);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        margin-right: 15px;
    }

    .user-name {
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 0.85rem;
        color: #8392ab;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Danh sách người dùng</h3>
            <span class="text-muted">Quản lý tài khoản và phân quyền hệ thống</span>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-4 py-2 shadow-sm rounded-3">
            <i class="bi bi-person-plus-fill me-2"></i>Thêm mới
        </a>
    </div>

    <div class="card card-custom bg-white">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 35%">Thành viên</th>
                        <th style="width: 15%">Vai trò</th>
                        <th style="width: 20%">Ngày tham gia</th>
                        <th style="width: 15%" class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><span class="text-muted fw-bold">#{{ $user->id }}</span></td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-email">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            @php
                                $isAdmin = ($user->role ?? 'user') === 'admin';
                            @endphp
                            <span class="badge rounded-pill {{ $isAdmin ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }} px-3 py-2 border {{ $isAdmin ? 'border-danger-subtle' : 'border-info-subtle' }}">
                                <i class="bi {{ $isAdmin ? 'bi-shield-lock-fill' : 'bi-person-fill' }} me-1"></i>
                                {{ $isAdmin ? 'Quản trị viên' : 'Thành viên' }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center text-secondary">
                                <i class="bi bi-calendar3 me-2"></i>
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                            <small class="text-muted ps-4">{{ $user->created_at ? $user->created_at->format('H:i') : '' }}</small>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-action btn-outline-primary me-2" title="Chỉnh sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không? Hành động này không thể hoàn tác.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-action btn-outline-danger" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection