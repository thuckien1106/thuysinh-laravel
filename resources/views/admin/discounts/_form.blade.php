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
        <input type="text" class="form-control" name="start_at" id="start_at"
               value="{{ old('start_at', isset($discount->start_at)?optional($discount->start_at)->format('Y-m-d H:i'):null) }}"
               placeholder="Chọn ngày giờ">
        <button class="btn btn-outline-secondary" type="button" data-action="now">Bây giờ</button>
        <button class="btn btn-outline-secondary" type="button" data-action="clear">Xóa</button>
      </div>
      <small class="text-muted">Để trống = bắt đầu ngay</small>
    </div>
    <div class="col-md-6">
      <label for="end_at" class="form-label">Kết thúc</label>
      <div class="input-group">
        <input type="text" class="form-control" name="end_at" id="end_at"
               value="{{ old('end_at', isset($discount->end_at)?optional($discount->end_at)->format('Y-m-d H:i'):null) }}"
               placeholder="Chọn ngày giờ" required>
        <button class="btn btn-outline-secondary" type="button" data-action="+1d">+1 ngày</button>
        <button class="btn btn-outline-secondary" type="button" data-action="+7d">+7 ngày</button>
        <button class="btn btn-outline-secondary" type="button" data-action="clear">Xóa</button>
      </div>
    </div>
  </div>
  <div class="mt-3 d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
    <button class="btn btn-ocean" id="discount-submit">Lưu</button>
  </div>
</form>

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const opts = {
        enableTime: true,
        time_24hr: true,
        dateFormat: 'Y-m-d H:i',
        altInput: true,
        altFormat: 'd/m/Y H:i',
        minuteIncrement: 5,
        locale: (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns.vn) || 'vn'
      };
      const $start = document.querySelector('#start_at');
      const $end = document.querySelector('#end_at');
      if (!window.flatpickr || !$start || !$end) return;

      const fpStart = flatpickr($start, Object.assign({}, opts, {
        defaultDate: $start.value || null
      }));
      const fpEnd = flatpickr($end, Object.assign({}, opts, {
        defaultDate: $end.value || null
      }));

      const clamp = () => {
        const s = fpStart.selectedDates[0];
        const e = fpEnd.selectedDates[0];
        if (s && e && e < s) fpEnd.setDate(s, true);
      };
      fpStart.config.onChange.push(clamp);
      fpEnd.config.onChange.push(clamp);

      document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
          const act = btn.dataset.action;
          if (act === 'now') {
            const now = new Date();
            fpStart.setDate(now, true);
            if (!fpEnd.selectedDates[0]) {
              const d = new Date(now);
              d.setDate(d.getDate() + 7);
              fpEnd.setDate(d, true);
            }
          } else if (act === '+1d' || act === '+7d') {
            const base = fpStart.selectedDates[0] || new Date();
            const d = new Date(base);
            d.setDate(d.getDate() + (act === '+1d' ? 1 : 7));
            fpEnd.setDate(d, true);
          } else if (act === 'clear') {
            const group = btn.closest('.input-group');
            if (group && group.querySelector('#start_at')) fpStart.clear();
            if (group && group.querySelector('#end_at')) fpEnd.clear();
          }
        });
      });
    });
  </script>
@endpush

