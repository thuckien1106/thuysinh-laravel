@extends('layouts.admin')
@section('title','Giảm giá sản phẩm')
@section('content')

<div class="admin-headerbar">
  <h3>Giảm giá sản phẩm</h3>
  <div>
    <a href="{{ route('admin.discounts.create') }}" class="btn btn-ocean"><i class="bi bi-plus-lg me-1"></i>Thêm giảm giá</a>
  </div>
  </div>

<form class="row g-2 mb-3" method="GET">
  <div class="col-md-3">
    <select name="status" class="form-select">
      <option value="">Tất cả trạng thái</option>
      <option value="active" @if($status==='active') selected @endif>Đang áp dụng</option>
      <option value="upcoming" @if($status==='upcoming') selected @endif>Sắp tới</option>
      <option value="expired" @if($status==='expired') selected @endif>Hết hạn</option>
    </select>
  </div>
  <div class="col-md-4">
    <select name="product_id" class="form-select">
      <option value="">Tất cả sản phẩm</option>
      @foreach($products as $p)
        <option value="{{ $p->id }}" @if($productId==$p->id) selected @endif>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2"><button class="btn btn-outline-ocean w-100">Lọc</button></div>
</form>

<div class="card p-3">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Sản phẩm</th>
          <th class="text-center">%</th>
          <th>Bắt đầu</th>
          <th>Kết thúc</th>
          <th>Ghi chú</th>
          <th class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($discounts as $d)
          <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->product->name ?? ('#'.$d->product_id) }}</td>
            <td class="text-center">-{{ $d->percent }}%</td>
            <td>{{ $d->start_at }}</td>
            <td>{{ $d->end_at }}</td>
            <td>{{ $d->note }}</td>
            <td class="text-end">
              <a href="{{ route('admin.discounts.edit',$d->id) }}" class="btn btn-sm btn-outline-ocean">Sửa</a>
              <form method="POST" action="{{ route('admin.discounts.destroy',$d->id) }}" class="d-inline" onsubmit="return confirm('Xóa giảm giá này?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">Chưa có giảm giá.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div>{{ $discounts->links() }}</div>
</div>
@endsection

