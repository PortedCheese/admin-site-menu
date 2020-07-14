@isset($menu)
    @foreach($menu as $item)
        @if ($item->template)
            @if ($gate = $item->gate)
                @can($gate)
                    @includeIf($item->template, ['ico' => $item->ico])
                @endcan
            @elseif ($role = $item->middleware)
                @role($role)
                    @includeIf($item->template, ['ico' => $item->ico])
                @endrole
            @else
                @includeIf($item->template, ['ico' => $item->ico])
            @endif
        @elseif ($item->url)
            @php($active = menuactive()->getActive($item))
            @if ($gate = $item->gate)
                @can($gate)
                    <li class="{{ menuactive()->getListClass($item, "nav-item") }}{{ $active ? " active" : "" }}">
                        @include('layouts.sb-admin.link', ['item' => $item, 'begin' => "nav-link", 'active' => $active])
                    </li>
                @endcan
            @elseif ($role = $item->middleware)
                @role($role)
                    <li class="{{ menuactive()->getListClass($item, "nav-item") }}{{ $active ? " active" : "" }}">
                        @include('layouts.sb-admin.link', ['item' => $item, 'begin' => "nav-link", 'active' => $active])
                    </li>
                @endrole
            @else
                <li class="{{ menuactive()->getListClass($item, "nav-item") }}{{ $active ? " active" : "" }}">
                    @include('layouts.sb-admin.link', ['item' => $item, 'begin' => "nav-link", 'active' => $active])
                </li>
            @endif
        @endif
    @endforeach
@endisset
