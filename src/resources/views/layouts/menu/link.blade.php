@php
    $class = $drop ? "dropdown-item" : "nav-link";
    $class .= $item->children ? ' dropdown-toggle' : '';
    $class .= $item->class ? " $item->class" : '';
@endphp
<a class="{{ $class }}"
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
   href="{{ $item->url }}">
    {{ $item->title }}
</a>
@if ($item->children)
    <div class="dropdown-menu" aria-labelledby="item-drop-{{ $item->id }}">
        @foreach($item->children as $child)
            @include('layouts.menu.link', ['item' => $child, 'drop' => true])
        @endforeach
    </div>
@endif