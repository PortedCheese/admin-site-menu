@extends('admin.layout')

@section('page-title', 'Редактировать пункт меню - ')
@section('header-title', 'Редактировать пункт меню ' . $menuItem->title)

@section('admin')
    <div class="col-12">
        <form method="post"
              action="{{ route('admin.menus.update-item', ['menuItem' => $menuItem]) }}"
              class="col-12">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
                <label for="title">Название</label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') ? old('title') : $menuItem->title}}"
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
                       value="{{ old('route') ? old('route') : $menuItem->route }}"
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
                       value="{{ old('url') ? old('url') : $menuItem->url }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="method">Метод</label>
                <input type="text"
                       id="method"
                       name="method"
                       value="{{ old('method') ? old('method') : $menuItem->method }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="class">Класс</label>
                <input type="text"
                       id="class"
                       name="class"
                       value="{{ old('class') ? old('class') : $menuItem->class }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="middleware">Роли</label>
                <input type="text"
                       id="middleware"
                       name="middleware"
                       value="{{ old('middleware') ? old('middleware') : $menuItem->middleware }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="weight">Вес</label>
                <input type="number"
                       id="weight"
                       name="weight"
                       value="{{ old('weight') ? old('weight') : $menuItem->weight }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="target">Target</label>
                <input type="text"
                       id="target"
                       name="target"
                       value="{{ old('target') ? old('target') : $menuItem->target }}"
                       class="form-control">
            </div>

            <div class="btn-group"
                 role="group">
                <a href="{{ route('admin.menus.index') }}"
                   class="btn btn-secondary">
                    Назад к списку меню
                </a>
                <button type="submit" class="btn btn-success">Обновить</button>
            </div>

        </form>
    </div>
@endsection