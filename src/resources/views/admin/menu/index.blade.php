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
                                    @if (! in_array($menu->key, ['main', 'admin']))
                                        <confirm-delete-model-button model-id="{{ $menu->id }}">
                                            <template slot="other">
                                                @include('admin-site-menu::admin.includes.menu-buttons', [
                                                    'menuId' => $menu->id,
                                                    'count' => false
                                                ])
                                            </template>
                                            @role('admin')
                                            <template slot="delete">
                                                <form action="{{ route('admin.menus.destroy', ['menu' => $menu]) }}"
                                                      id="delete-{{ $menu->id }}"
                                                      class="btn-group"
                                                      method="post">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                            </template>
                                            @endrole
                                        </confirm-delete-model-button>
                                    @else
                                        <div class="btn-group" role="group">
                                            @include('admin-site-menu::admin.includes.menu-buttons', [
                                                'menuId' => $menu->id,
                                                'count' => false
                                            ])
                                        </div>
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