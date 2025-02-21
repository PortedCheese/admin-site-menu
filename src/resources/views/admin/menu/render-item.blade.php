<tr @if ($parent) id="{{ $parent }}" class="collapse table-secondary" @endif >
    {{-- Пункт меню --}}
    <td>
        {{ $menuItem->title }} {{ !$parent ? "(" . $menuItem->children->count() . ")" : "" }}
    </td>
    {{-- URL --}}
    <td>
        @if ($url = $menuItem->getUrl())
            <a href="{{ $url }}" target="_blank">
                {{ $url }}
            </a>
        @else
            Не определено
        @endif
    </td>
    {{-- Вес --}}
    <td>
        <change-menu-weight csrf-token="{{ csrf_token() }}"
                            url="{{ route('admin.vue.menu.weight', ['menuItem' => $menuItem->id]) }}"
                            weight="{{ $menuItem->weight }}"
                            item-id="{{ $menuItem->id }}">
        </change-menu-weight>
    </td>
    {{-- Действия --}}
    <td>
        <div role="toolbar" class="btn-toolbar">
            <div class="btn-group btn-group-sm mr-1">
                <a href="{{ route('admin.menus.edit-item', ['menuItem' => $menuItem]) }}" class="btn btn-primary">
                    <i class="far fa-edit"></i>
                </a>
                <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$menuItem->id}" }}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            @if (! $parent)
                <div class="btn-group btn-group-sm">
                    @if ($menuItem->children->count())
                        <a href="#collapse-menu-item-list-{{ $menuItem->id }}"
                           data-bs-toggle="collapse"
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
                </div>
            @endif
        </div>
        <confirm-form :id="'{{ "delete-form-{$menuItem->id}" }}'">
            <template>
                <form action="{{ route('admin.menus.destroy-item', ['menuItem' => $menuItem]) }}"
                      id="delete-form-{{ $menuItem->id }}"
                      class="btn-group"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            </template>
        </confirm-form>

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