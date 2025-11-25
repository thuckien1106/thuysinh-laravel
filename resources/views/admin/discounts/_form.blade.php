<form id="discount-form" method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="row g-4">
        {{-- Product Select --}}
        <div class="col-md-8">
            <label class="form-label small fw-bold text-uppercase text-secondary">
                Sản phẩm áp dụng <span class="text-danger">*</span>
            </label>
            <select name="product_id" class="form-select" {{ isset($readonlyProduct)&&$readonlyProduct?'disabled':'' }} required>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" @if(old('product_id', $discount->product_id ?? '')==$p->id) selected @endif>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @if(isset($readonlyProduct)&&$readonlyProduct)
                <input type="hidden" name="product_id" value="{{ $discount->product_id }}">
            @endif
        </div>

        {{-- Percent --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-uppercase text-secondary">
                Mức giảm (%) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="number" min="1" max="90" name="percent" class="form-control fw-bold text-primary" 
                       value="{{ old('percent', $discount->percent ?? '') }}" placeholder="VD: 10" required>
                <span class="input-group-text bg-light text-muted">%</span>
            </div>
        </div>

        {{-- Start Date --}}
        <div class="col-md-6">
            <label for="start_at" class="form-label small fw-bold text-uppercase text-secondary">
                Bắt đầu <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                <input type="text" class="form-control js-dtp border-start-0 ps-2" name="start_at" id="start_at"
                       value="{{ old('start_at', isset($discount->start_at)?optional($discount->start_at)->format('Y-m-d H:i'):null) }}"
                       placeholder="YYYY-MM-DD HH:MM">
                <button class="btn btn-light border text-muted" type="button" data-open="start_at" title="Chọn lịch">
                    <i class="bi bi-clock"></i>
                </button>
            </div>
        </div>

        {{-- End Date --}}
        <div class="col-md-6">
            <label for="end_at" class="form-label small fw-bold text-uppercase text-secondary">
                Kết thúc <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-x"></i></span>
                <input type="text" class="form-control js-dtp border-start-0 ps-2" name="end_at" id="end_at"
                       value="{{ old('end_at', isset($discount->end_at)?optional($discount->end_at)->format('Y-m-d H:i'):null) }}"
                       placeholder="YYYY-MM-DD HH:MM" required>
                <button class="btn btn-light border text-muted" type="button" data-open="end_at" title="Chọn lịch">
                    <i class="bi bi-clock"></i>
                </button>
            </div>
        </div>

        {{-- Note --}}
        <div class="col-12">
            <label class="form-label small fw-bold text-uppercase text-secondary">Ghi chú</label>
            <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú nội bộ (Tùy chọn)">{{ old('note', $discount->note ?? '') }}</textarea>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light border fw-semibold text-secondary px-3" data-bs-dismiss="modal">
            Đóng
        </button>
        <button class="btn btn-primary fw-bold px-4 border-0" id="discount-submit" 
                style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <i class="bi bi-save me-1"></i> Lưu thông tin
        </button>
    </div>
</form>