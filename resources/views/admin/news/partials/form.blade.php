@php
  $item = $news;
@endphp
<div class="row g-3">
  <div class="col-md-8">
    <div class="mb-3">
      <label class="form-label">Tiêu đề</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
      @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Slug (để trống để tự tạo)</label>
      <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}">
      @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Tóm tắt</label>
      <textarea name="summary" class="form-control" rows="2">{{ old('summary', $item->summary ?? '') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Nội dung</label>
      <textarea name="content" class="form-control" rows="8">{{ old('content', $item->content ?? '') }}</textarea>
    </div>
  </div>

  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Ảnh banner (URL)</label>
      <input type="text" name="banner_image" class="form-control" value="{{ old('banner_image', $item->banner_image ?? '') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Thời gian đăng</label>
      <input type="datetime-local" name="published_at" class="form-control"
        value="{{ old('published_at', isset($item->published_at) ? $item->published_at->format('Y-m-d\\TH:i') : '') }}">
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
        {{ old('is_published', $item->is_published ?? false) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_published">
        Hiển thị (đăng)
      </label>
    </div>
  </div>
</div>
