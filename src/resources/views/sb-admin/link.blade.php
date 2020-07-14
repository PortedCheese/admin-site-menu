<a class="{{ menuactive()->getLinkClass($item, $active, $begin) }}"
   @if ($item->target)
   target="{{ $item->target }}"
   @endif
   @if ($item->children)
   data-toggle="collapse"
   data-target="#collapse-{{ $item->id }}"
   aria-controls="#collapse-{{ $item->id }}"
   aria-expanded="{{ $active ? "true" : "false" }}"
   @endif
   href="{{ $item->route ? route($item->route) : ($item->url ? $item->url : "#")}}">
    @if ($item->ico)
        <i class="{{ $item->ico }}"></i>
    @endif
    {{ $item->title }}
</a>
@if ($item->children)
    <div id="{{ "collapse-$item->id" }}" class="collapse{{ $active ? " show" : "" }}" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @foreach($item->children as $child)
                @include('admin-site-menu::sb-admin.link', ['item' => $child, 'begin' => "collapse-item", 'active' => menuactive()->getActive($child)])
            @endforeach
        </div>
    </div>
@endif