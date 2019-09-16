@extends('admin.layout')

@section('page-title', 'Просмотр меню - ')
@section('header-title', "Просмотр меню {$menu->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.menus.create-item', ['menu' => $menu]) }}"
                   class="btn btn-success">
                    Добавить
                </a>
            </div>
            <div class="card-body">
                @include('admin-site-menu::admin.item.table')
            </div>
        </div>
    </div>
@endsection
