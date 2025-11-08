@php($now = now())
@php($start = \Carbon\Carbon::parse($d->start_at))
@php($end = \Carbon\Carbon::parse($d->end_at))
<tr id="row-{{ $d->id }}">
  <td>{{ $d->id }}</td>
  <td>{{ $d->product->name ?? ('#'.$d->product_id) }}</td>
  <td class="text-center">-{{ $d->percent }}%</td>
  <td>
    @if($now->lt($start))
      <span class="badge bg-secondary">Chưa đến giờ</span>
    @elseif($now->gt($end))
      <span class="badge bg-danger">Đã hết hạn</span>
    @else
      <span class="badge bg-success">Đang hiệu lực</span>
    @endif
  </td>
  <td>@dt($d->start_at)</td>
  <td>@dt($d->end_at)</td>
  <td>{{ $d->note }}</td>
  <td class="text-end">
    <button class="btn btn-sm btn-outline-ocean js-edit-discount" data-id="{{ $d->id }}">Sửa</button>
    <form method="POST" action="{{ route('admin.discounts.destroy',$d->id) }}" class="d-inline" onsubmit="return confirm('Xóa giảm giá này?')">
      @csrf @method('DELETE')
      <button class="btn btn-sm btn-outline-danger">Xóa</button>
    </form>
  </td>
</tr>

