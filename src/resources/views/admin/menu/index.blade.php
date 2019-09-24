@extends('admin.layout')

@section('page-title', 'Меню - ')
@section('header-title', 'Список меню сайта')

@section('admin')
    @role('admin')
        <div class="col-12">
            <div class="card">
                <div class="card-body row">
                    @include('admin-site-menu::admin.includes.export-btn')
                    @include('admin-site-menu::admin.includes.create-form')
                </div>
            </div>
        </div>
    @endrole

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Ключ</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->title }} ({{ $menu->items->count() }})</td>
                                <td>{{ $menu->key }}</td>
                                <td>
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group btn-group-sm mr-1">
                                            @include('admin-site-menu::admin.includes.menu-buttons', [
                                                'menuId' => $menu->id,
                                                'count' => false
                                            ])
                                            @if (! in_array($menu->key, ['main', 'admin']))
                                                @role('admin')
                                                    <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$menu->id}" }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endrole
                                            @endif
                                        </div>
                                    </div>
                                    @if (! in_array($menu->key, ['main', 'admin']))
                                        @role('admin')
                                            <confirm-form :id="'{{ "delete-form-{$menu->id}" }}'">
                                                <template>
                                                    <form action="{{ route('admin.menus.destroy', ['menu' => $menu]) }}"
                                                          id="delete-form-{{ $menu->id }}"
                                                          class="btn-group"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endrole
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection