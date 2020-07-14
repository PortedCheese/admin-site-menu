<a class="nav-link"
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
    <span>{{ $item->title }}</span>
</a>
@if ($item->children)
    <div id="{{ "collapse-$item->id" }}" class="collapse{{ $active ? " show" : "" }}" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @foreach($item->children as $child)
                <a class="collapse-item{{ menuactive()->getActive($child) ? " active" : "" }}" href="{{ $child->route ? route($child->route) : ($child->url ? $child->url : "#")}}">
                    {{ $child->title }}
                </a>
            @endforeach
        </div>
    </div>
@endif