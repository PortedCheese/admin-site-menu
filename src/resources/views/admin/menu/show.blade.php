@extends('admin.layout')

@section('page-title', 'Просмотр меню - ')
@section('header-title', "Просмотр меню {$menu->title}")

@section('admin')
    <div class="col-12">
        @include('admin-site-menu::admin.menu.items-table')
    </div>
@endsection
