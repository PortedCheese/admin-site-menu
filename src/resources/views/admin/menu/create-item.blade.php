@extends('admin.layout')

@section('page-title', 'Новый пункт меню - ')
@section('header-title', 'Создать новый пункт для ' . $menu->title)

@section('admin')
    <div class="col-12">
        <form method="post"
              action="{{ route('admin.menus.store-item', ['menu' => $menu]) }}"
              class="col-12">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
            <input type="hidden" name="parent_id" value="{{ $parent }}">
            <div class="form-group">
                <label for="title">Название</label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       required
                       class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">
                @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                @endif
            </div>

            <div class="form-group">
                <label for="route">Route</label>
                <input type="text"
                       id="route"
                       name="route"
                       value="{{ old('route') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                @include('admin-site-menu::admin.menu.routes-modal')
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="text"
                       id="url"
                       name="url"
                       value="{{ old('url') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="method">Метод</label>
                <input type="text"
                       id="method"
                       name="method"
                       value="{{ old('method') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="middleware">Роли</label>
                <input type="text"
                       id="middleware"
                       name="middleware"
                       value="{{ old('middleware') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="class">Класс</label>
                <input type="text"
                       id="class"
                       name="class"
                       value="{{ old('class') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="target">Target</label>
                <input type="text"
                       id="target"
                       name="target"
                       value="{{ old('target') }}"
                       class="form-control">
            </div>

            <div class="btn-group"
                 role="group">
                <a href="{{ route('admin.menus.index') }}"
                   class="btn btn-secondary">
                    Назад к списку меню
                </a>
                <button type="submit" class="btn btn-success">Создать</button>
            </div>

        </form>
    </div>
@endsection