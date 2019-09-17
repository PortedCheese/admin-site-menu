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
            @if ($role = $item->middleware)
                @role($role)
                <li class="{{ menuactive()->getListClass($item, "nav-item") }}">
                    @include('admin-site-menu::layouts.link', ['item' => $item, 'begin' => "nav-link", 'active' => menuactive()->getActive($item)])
                </li>
                @endrole
            @else
                <li class="{{ menuactive()->getListClass($item, "nav-item") }}">
                    @include('admin-site-menu::layouts.link', ['item' => $item, 'begin' => "nav-link", 'active' => menuactive()->getActive($item)])
                </li>
            @endif
        @endif
    @endforeach
@endisset
