<a class="{{ menuactive()->getLinkClass($item, $active, $begin) }} {{ $item->url ? ($item->url == "/".Request::path() ? "active" : "") : "" }}"
   @if ($item->target)
   target="{{ $item->target }}"
   @endif
   @if ($item->children)
   id="item-drop-{{ $item->id }}"
   role="button"
   data-bs-toggle="dropdown"
   aria-haspopup="true"
   aria-expanded="false"
   @endif
   href="{{ $item->route ? route($item->route) : ($item->url ? $item->url : "#")}}">
    @if ($item->ico)
        <i class="{{ $item->ico }}"></i>
    @endif
    {{ $item->title }}
</a>
@if ($item->children)
    <div class="dropdown-menu" aria-labelledby="item-drop-{{ $item->id }}">
        @foreach($item->children as $child)
            @include('admin-site-menu::layouts.link', ['item' => $child, 'begin' => "dropdown-item", 'active' => menuactive()->getActive($child)])
        @endforeach
    </div>
@endif