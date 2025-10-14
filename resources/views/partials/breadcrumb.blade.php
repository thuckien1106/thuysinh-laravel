@if(isset($items) && count($items))
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      @foreach($items as $i => $item)
        @if(!empty($item['url']) && $i < count($items) - 1)
          <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
        @else
          <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
        @endif
      @endforeach
    </ol>
  </nav>
@endif

