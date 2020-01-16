@if ($count)
    <a href="#collapse-list-{{ $menuId }}"
       data-toggle="collapse"
       role="button"
       class="btn btn-secondary">
        <i class="fas fa-stream"></i>
    </a>
@endif
@can("view", $menu)
    <a href="{{ route('admin.menus.show', ['menu' => $menu->id]) }}" class="btn btn-dark">
        <i class="far fa-eye"></i>
    </a>
@endcan
@can("createItem", $menu)
    <a href="{{ route('admin.menus.create-item', ['menu' => $menuId]) }}"
       class="btn btn-success">
        <i class="fas fa-plus"></i>
    </a>
@endcan
