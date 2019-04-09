@foreach($menu as $item)
    @php
        $class = "nav-item";
        $class .= $currentRoute == $item->route ? ' active' : '';
        $class .= $item->children ? ' dropdown' : '';
    @endphp
    <li class="{{ $class }}">
        @if ($role = $item->middleware)
            @role($role)
                @if ($item->url)
                    @include('layouts.menu.link', ['item' => $item, 'drop' => false])
                @endif
            @endrole
        @elseif ($item->url)
            @include('layouts.menu.link', ['item' => $item, 'drop' => false])
        @endif
    </li>
@endforeach