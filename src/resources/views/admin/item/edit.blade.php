@extends('admin.layout')

@section('page-title', 'Редактировать пункт меню - ')
@section('header-title', 'Редактировать пункт меню ' . $menuItem->title)

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="{{ route('admin.menus.update-item', ['menuItem' => $menuItem]) }}"
                      class="col-12">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-group">
                        @include('admin-site-menu::admin.includes.routes-modal')
                    </div>

                    <ul class="nav nav-tabs mb-3" id="menuItemTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="base-tab" data-toggle="tab" href="#base" role="tab" aria-selected="true">Основные</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="extra-tab" data-toggle="tab" href="#extra" role="tab" aria-selected="false">Extra</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="style-tab" data-toggle="tab" href="#style" role="tab" aria-selected="false">Стили</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="menuItemTabContent">
                        <div class="tab-pane fade show active" id="base" role="tabpanel" aria-labelledby="base-tab">
                            <div class="form-group">
                                <label for="title">Заголовок <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="title"
                                       name="title"
                                       required
                                       value="{{ old("title", $menuItem->title) }}"
                                       class="form-control @error("title") is-invalid @enderror">
                                @error("title")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="route">Route</label>
                                <input type="text"
                                       id="route"
                                       name="route"
                                       value="{{ old("route", $menuItem->route) }}"
                                       class="form-control @error("route") is-invalid @enderror">
                                @error("route")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text"
                                       id="url"
                                       name="url"
                                       value="{{ old("url", $menuItem->url) }}"
                                       class="form-control @error("url") is-invalid @enderror">
                                @error("url")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="single"
                                           {{ (! count($errors->all()) && $menuItem->single) || old("single") ? "checked" : "" }}
                                           name="single">
                                    <label class="custom-control-label" for="single">Отдельная страница</label>
                                </div>
                                <small class="form-text text-muted">Указать что при подсветке этой страницы не учитывать страницы с похожим началом путей</small>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="extra" role="tabpanel" aria-labelledby="extra-tab">
                            <div class="form-group">
                                <label for="template">Шаблон</label>
                                <input type="text"
                                       id="template"
                                       name="template"
                                       value="{{ old("template", $menuItem->template) }}"
                                       class="form-control @error("template") is-invalid @enderror">
                                @error("template")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Шаблон, в котором задано меню</small>
                            </div>

                            <div class="form-group">
                                <label for="method">Метод</label>
                                <input type="text"
                                       id="method"
                                       name="method"
                                       value="{{ old("method", $menuItem->method) }}"
                                       class="form-control @error("method") is-invalid @enderror">
                                @error("method")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Если в классе указан метод для формирования меню</small>
                            </div>

                            <div class="form-group">
                                <label for="middleware">Роли</label>
                                <input type="text"
                                       id="middleware"
                                       name="middleware"
                                       value="{{ old("middleware", $menuItem->middleware) }}"
                                       class="form-control @error("middleware") is-invalid @enderror">
                                @error("middleware")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Указать роли, для которых нужно показывать меню, если пусто то нет проверки на роли</small>
                            </div>

                            <div class="form-group">
                                <label for="active_state">Active state</label>
                                <textarea type="text"
                                       id="active_state"
                                       name="active_state"
                                       class="form-control @error("active_state") is-invalid @enderror">{{ old("active_state", $menuItem->active_state) }}</textarea>
                                @error("active_state")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Если нужно подсветить элемент меню на страницах, route которых отличается, нужно указать список этих путей через "|"</small>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="style" role="tabpanel" aria-labelledby="style-tab">
                            <div class="form-group">
                                <label for="class">Класс</label>
                                <input type="text"
                                       id="class"
                                       name="class"
                                       value="{{ old("class", $menuItem->class) }}"
                                       class="form-control @error("class") is-invalid @enderror">
                                @error("class")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Можно задать дополнительный класс для элемента меню ( применится к ссылке )</small>
                            </div>

                            <div class="form-group">
                                <label for="ico">Icon</label>
                                <input type="text"
                                       id="ico"
                                       name="ico"
                                       value="{{ old("ico", $menuItem->ico) }}"
                                       class="form-control @error("ico") is-invalid @enderror">
                                @error("ico")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Внутри ссылки можно добавить элемент "i" с классом указаным в поле</small>
                            </div>

                            <div class="form-group">
                                <label for="target">Target</label>
                                <input type="text"
                                       id="target"
                                       name="target"
                                       value="{{ old("target", $menuItem->target) }}"
                                       class="form-control @error("target") is-invalid @enderror">
                                @error("target")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Аттрибут "target" для ссылки</small>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group"
                         role="group">
                        <a href="{{ route('admin.menus.index') }}"
                           class="btn btn-secondary">
                            Назад к списку меню
                        </a>
                        <a href="{{ route("admin.menus.show", ['menu' => $menu]) }}" class="btn btn-dark">
                            {{ $menu->title }}
                        </a>
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection