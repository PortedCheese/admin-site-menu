<a class="{{ menuactive()->getLinkClass($item, $active, $begin) }}"
   @if ($item->target)
   target="{{ $item->target }}"
   @endif
   @if ($item->children)
   id="item-drop-{{ $item->id }}"
   role="button"
   data-toggle="dropdown"
   aria-haspopup="true"
   aria-expanded="false"
   @endif
   href="{{ $item->route ? route($item->route) : "#"}}">
    @if ($item->ico)
        <i class="{{ $item->ico }}"></i>
    @endif
    {{ $item->title }}
</a>
@if ($item->children)
    <div class="dropdown-menu" aria-labelledby="item-drop-{{ $item->id }}">
        @foreach($item->children as $child)
            @include('admin-site-menu::layouts.link', ['item' => $child, 'begin' => "dropdown-item", 'active' => false])
        @endforeach
    </div>
@endif