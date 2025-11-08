<form id="discount-form" method="POST" action="{{ $action }}">
  @csrf
  @if($method !== 'POST') @method($method) @endif
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Sản phẩm</label>
      <select name="product_id" class="form-select" {{ isset($readonlyProduct)&&$readonlyProduct?'disabled':'' }} required>
        @foreach($products as $p)
          <option value="{{ $p->id }}" @if(old('product_id', $discount->product_id ?? '')==$p->id) selected @endif>{{ $p->name }}</option>
        @endforeach
      </select>
      @if(isset($readonlyProduct)&&$readonlyProduct)
        <input type="hidden" name="product_id" value="{{ $discount->product_id }}">
      @endif
    </div>
    <div class="col-md-2">
      <label class="form-label">Phần trăm</label>
      <input type="number" min="1" max="90" name="percent" class="form-control" value="{{ old('percent', $discount->percent ?? '') }}" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Ghi chú</label>
      <input type="text" name="note" class="form-control" value="{{ old('note', $discount->note ?? '') }}" placeholder="Tùy chọn">
    </div>
    <div class="col-md-6">
      <label for="start_at" class="form-label">Bắt đầu</label>
      <div class="input-group">
        <input type="text" class="form-control js-dtp" name="start_at" id="start_at"
               value="{{ old('start_at', isset($discount->start_at)?optional($discount->start_at)->format('Y-m-d H:i'):null) }}"
               placeholder="Chọn ngày giờ">
        <button class="btn btn-outline-secondary" type="button" data-open="start_at" aria-label="Mở lịch">
          <i class="bi bi-calendar-event"></i>
        </button>
      </div>
      
    </div>
    <div class="col-md-6">
      <label for="end_at" class="form-label">Kết thúc</label>
      <div class="input-group">
        <input type="text" class="form-control js-dtp" name="end_at" id="end_at"
               value="{{ old('end_at', isset($discount->end_at)?optional($discount->end_at)->format('Y-m-d H:i'):null) }}"
               placeholder="Chọn ngày giờ" required>
        <button class="btn btn-outline-secondary" type="button" data-open="end_at" aria-label="Mở lịch">
          <i class="bi bi-calendar-event"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="mt-3 d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
    <button class="btn btn-ocean" id="discount-submit">Lưu</button>
  </div>
</form>


