@isset($menu)
    @foreach($menu as $item)
        @if ($item->template)
            @if ($role = $item->middleware)
                @role($role)
                    @includeIf($item->template, ['ico' => $item->ico])
                @endrole
            @else
                @includeIf($item->template, ['ico' => $item->ico])
            @endif
        @elseif ($item->url)
            @php
                $class = "nav-item";
                $class .= $currentRoute == $item->route ? ' active' : '';
                $class .= $item->children ? ' dropdown' : '';

                $active = false;
                if ($item->activeChild) {
                    // Разделить текущий роут.
                    $exploded = explode('.', $currentRoute);
                    $route = [];
                    for ($i = 0; $i < count($exploded) - 1; $i++) {
                        $route[] = $exploded[$i];
                    }
                    if (! empty($route)) {
                        $explodedCurrentRoute = implode('.', $route);
                        $active = in_array($explodedCurrentRoute, $item->activeChild);
                    }
                    else {
                        $active = $currentRoute == $item->route;
                    }
                }
            @endphp
            @if ($role = $item->middleware)
                @role($role)
                <li class="{{ $class }}">
                    @include('layouts.menu.link', ['item' => $item, 'drop' => false, 'active' => $active])
                </li>
                @endrole
            @else
                <li class="{{ $class }}">
                    @include('layouts.menu.link', ['item' => $item, 'drop' => false, 'active' => $active])
                </li>
            @endif
        @endif
    @endforeach
@endisset
