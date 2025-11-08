@extends('layouts.admin')
@section('title','Giảm giá sản phẩm')
@section('content')

<div class="admin-headerbar">
  <h3>Giảm giá sản phẩm</h3>
  <div>
    <button class="btn btn-ocean" id="js-open-create">
      <i class="bi bi-plus-lg me-1"></i>Thêm giảm giá
    </button>
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
  <div class="col-md-2">
    <button class="btn btn-outline-ocean w-100">Lọc</button>
  </div>
</form>

<div class="card p-3">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Sản phẩm</th>
          <th class="text-center">%</th>
          <th>Trạng thái</th>
          <th>Bắt đầu</th>
          <th>Kết thúc</th>
          <th>Ghi chú</th>
          <th class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($discounts as $d)
          @include('admin.discounts._row', ['d'=>$d])
        @empty
          <tr><td colspan="8" class="text-center text-muted">Chưa có giảm giá.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div>{{ $discounts->links() }}</div>
</div>
@endsection

<!-- Modal AJAX tạo/sửa giảm giá -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Giảm giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"><div class="text-center text-muted">Đang tải...</div></div>
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
      body.innerHTML = '<div class="text-center text-muted">Đang tải...</div>';
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
            '<div class="alert alert-danger">'+err.message+'</div>');
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
