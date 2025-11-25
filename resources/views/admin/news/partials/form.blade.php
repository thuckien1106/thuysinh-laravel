@php
  $item = $news;
@endphp
<div class="mb-3">
  <label class="form-label">Tiêu đề</label>
  <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
  @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Nội dung</label>
  <textarea name="content" class="form-control" rows="8" required>{{ old('content', $item->content ?? '') }}</textarea>
  @error('content')<div class="text-danger small">{{ $message }}</div>@enderror
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
