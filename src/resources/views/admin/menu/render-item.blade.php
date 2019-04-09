<tr
        @if ($parent)
            id="{{ $parent }}"
            class="collapse table-secondary"
        @endif
>
    <td>
        {{ $menuItem->title }} {{ !$parent ? "(" . $menuItem->children->count() . ")" : "" }}
    </td>
    <td>
        @if ($url = $menuItem->getUrl())
            <a href="{{ $url }}" target="_blank">
                {{ $url }}
            </a>
        @else
            Не определено
        @endif
    </td>
    <td>
        <change-menu-weight csrf-token="{{ csrf_token() }}"
                            url="{{ route('admin.vue.menu.weight', ['menuItem' => $menuItem->id]) }}"
                            weight="{{ $menuItem->weight }}"
                            item-id="{{ $menuItem->id }}">

        </change-menu-weight>
    </td>
    <td>{{ $menuItem->class }}</td>
    <td>{{ $menuItem->middleware }}</td>
    <td>{{ $menuItem->target }}</td>
    <td>{{ $menuItem->method }}</td>
    <td>
        <confirm-delete-model-button model-id="{{ $menuItem->id }}">
            @if (!$parent)
                <template slot="other">
                    @if ($menuItem->children->count())
                        <a href="#collapse-menu-item-list-{{ $menuItem->id }}"
                           data-toggle="collapse"
                           role="button"
                           class="btn btn-secondary">
                            <i class="fas fa-stream"></i>
                        </a>
                    @endif
                    @if (!$menuItem->method)
                        <a href="{{ route('admin.menus.create-child-item', ['menu' => $menu->id, 'item' => $menuItem->id]) }}"
                           class="btn btn-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                </template>
            @endif
            <template slot="edit">
                <a href="{{ route('admin.menus.edit-item', ['menuItem' => $menuItem]) }}" class="btn btn-primary">
                    <i class="far fa-edit"></i>
                </a>
            </template>
            <template slot="delete">
                <form action="{{ route('admin.menus.destroy-item', ['menuItem' => $menuItem]) }}"
                      id="delete-{{ $menuItem->id }}"
                      class="btn-group"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            </template>
        </confirm-delete-model-button>
    </td>
</tr>
@if ($menuItem->children->count() && !$parent)
    @foreach($menuItem->children->sortBy('weight') as $item)
        @include('admin-site-menu::admin.menu.render-item', [
            'menuItem' => $item,
            'parent' => "collapse-menu-item-list-$menuItem->id"
        ])
    @endforeach
@endif