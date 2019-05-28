@extends('admin.layout')

@section('page-title', 'Просмотр меню - ')
@section('header-title', "Просмотр меню {$menu->title}")

@section('admin')
    <div class="col-12">
        <a href="{{ route('admin.menus.create-item', ['menu' => $menu]) }}"
           class="btn btn-success">
            Добавить
        </a>
    </div>
    <div class="col-12">
        @include('admin-site-menu::admin.menu.items-table')
    </div>
@endsection
