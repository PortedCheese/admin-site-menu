<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Пункт меню</th>
            <th>URL</th>
            <th>Вес</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($menu->items->where('parent_id', NULL)->sortBy('weight') as $item)
            @include('admin-site-menu::admin.menu.render-item', ['menuItem' => $item, 'parent' => false])
        @endforeach
        </tbody>
    </table>
</div>