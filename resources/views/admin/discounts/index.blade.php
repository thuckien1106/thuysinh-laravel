@extends('layouts.admin')
@section('title','Quản lý giảm giá')
@section('content')

<style>
    /* ================= DISCOUNT PAGE THEME ================= */
    :root {
        --table-header-bg: #f8fafc;
        --border-color: #e2e8f0;
        --primary-color: #3b82f6;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
    }
    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
    }
    .search-select {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .search-select:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Table Styling */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .discount-table th {
        background-color: var(--table-header-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #475569;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .discount-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
    }
    
    .discount-table tr:hover td {
        background-color: #f8fafc;
    }

    /* Button Styling */
    .btn-create {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        transition: transform 0.2s;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h3 class="fw-bold m-0 text-dark">Chương trình giảm giá</h3>
        <small class="text-muted">Quản lý các đợt khuyến mãi sản phẩm</small>
    </div>
    <div>
        <button class="btn-create" id="js-open-create">
            <i class="bi bi-plus-lg me-1"></i> Thêm giảm giá
        </button>
    </div>
</div>

{{-- FILTER SECTION --}}
<div class="filter-card">
    <form class="row g-3 align-items-end" method="GET">
        <!-- Trạng thái -->
        <div class="col-md-3">
            <span class="filter-label">Trạng thái</span>
            <select name="status" class="form-select search-select">
                <option value="">-- Tất cả --</option>
                <option value="active" @if($status==='active') selected @endif>Đang áp dụng</option>
                <option value="upcoming" @if($status==='upcoming') selected @endif>Sắp tới</option>
                <option value="expired" @if($status==='expired') selected @endif>Hết hạn</option>
            </select>
        </div>

        <!-- Sản phẩm -->
        <div class="col-md-4">
            <span class="filter-label">Sản phẩm</span>
            <select name="product_id" class="form-select search-select">
                <option value="">-- Tất cả sản phẩm --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" @if($productId==$p->id) selected @endif>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Nút Lọc -->
        <div class="col-md-2">
            <button class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; height: 42px;">Lọc</button>
        </div>
    </form>
</div>

{{-- TABLE SECTION --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table discount-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">#</th>
                    <th>Sản phẩm</th>
                    <th class="text-center">% Giảm</th>
                    <th>Trạng thái</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Ghi chú</th>
                    <th class="text-end pe-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discounts as $d)
                    {{-- Giữ nguyên include để đảm bảo logic Ajax row --}}
                    @include('admin.discounts._row', ['d'=>$d])
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-tag fs-1 text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted fw-bold">Chưa có chương trình giảm giá nào</h6>
                                <p class="text-muted small mb-0">Hãy tạo mới để bắt đầu khuyến mãi.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($discounts->hasPages())
        <div class="p-3 border-top">
            {{ $discounts->links() }}
        </div>
    @endif
</div>
@endsection

<!-- Modal AJAX tạo/sửa giảm giá -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header bg-light border-bottom-0 py-3">
        <h5 class="modal-title fw-bold text-dark">Thông tin giảm giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          <div class="text-center text-muted py-4">
              <div class="spinner-border text-primary mb-2" role="status"></div>
              <div>Đang tải dữ liệu...</div>
          </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  function boot(){
    const modalEl = document.getElementById("discountModal");
    if(!modalEl) return;

    const bs = window.bootstrap || (window.parent && window.parent.bootstrap);
    if(!bs){ window.addEventListener("load", boot); return; }

    const modal = new bs.Modal(modalEl);
    const body = modalEl.querySelector(".modal-body");
    const openCreate = document.getElementById("js-open-create");

    async function openForm(url){
      body.innerHTML = '<div class="text-center text-muted py-4"><div class="spinner-border text-primary mb-2"></div><div>Đang tải...</div></div>';
      const res = await fetch(url, {headers:{"X-Requested-With":"XMLHttpRequest"}});
      body.innerHTML = await res.text();
      if (window.initDiscountDatetime) {
        try { window.initDiscountDatetime(body); } catch(_) {}
      }

      const form = body.querySelector("#discount-form");
      if(!form){ modal.show(); return; }

      form.addEventListener("submit", async function(e){
        e.preventDefault();
        const action = form.getAttribute("action");
        const fd = new FormData(form);
        try{
          const resp = await fetch(action, {
            method: 'POST',
            headers: {
              "X-Requested-With":"XMLHttpRequest",
              "X-CSRF-TOKEN":document.querySelector('meta[name=csrf-token]')?.content||''
            },
            body: fd
          });
          if(!resp.ok){
            const data = await resp.json().catch(()=>({message:"Lỗi"}));
            throw new Error(data.message || "Đã có lỗi xảy ra");
          }
          try{
            const data = await resp.json();
            if(data && data.ok && data.html){
              const tbody = document.querySelector("table tbody");
              if(document.getElementById("row-"+data.id))
                document.getElementById("row-"+data.id).outerHTML = data.html;
              else
                tbody.insertAdjacentHTML("afterbegin", data.html);
            } else {
              location.reload();
            }
          }catch(_){ location.reload(); }
          modal.hide();
        }catch(err){
          const old = body.querySelector(".alert.alert-danger");
          if(old) old.remove();
          body.insertAdjacentHTML("afterbegin",
            '<div class="alert alert-danger rounded-3">'+err.message+'</div>');
        }
      });
      modal.show();
    }

    openCreate?.addEventListener("click", function(){
      openForm("{{ route('admin.discounts.modal.create') }}");
    });

    document.addEventListener("click", function(e){
      const btn = e.target.closest(".js-edit-discount");
      if(btn){
        const id = btn.getAttribute("data-id");
        return openForm(`{{ url('admin/discounts') }}/${id}/modal/edit`);
      }
      const a = e.target.closest("a");
      if(a && a.getAttribute("href") && /\/admin\/discounts\/(\d+)\/edit$/.test(a.getAttribute("href"))){
        e.preventDefault();
        const id = (a.getAttribute("href").match(/\/(\d+)\/edit$/)||[])[1];
        if(id) openForm(`{{ url('admin/discounts') }}/${id}/modal/edit`);
      }
    });
  }
  if(document.readyState === "complete") boot();
  else window.addEventListener("load", boot);
})();
</script>
@endpush