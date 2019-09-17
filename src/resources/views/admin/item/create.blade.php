@extends('admin.layout')

@section('page-title', 'Новый пункт меню - ')
@section('header-title', 'Создать новый пункт для ' . $menu->title)

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="{{ route('admin.menus.store-item', ['menu' => $menu]) }}"
                      class="col-12">
                    @csrf

                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <input type="hidden" name="parent_id" value="{{ $parent }}">

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
                                       required
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
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
                                       value="{{ old('route') }}"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                @include('admin-site-menu::admin.includes.routes-modal')
                            </div>

                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text"
                                       id="url"
                                       name="url"
                                       value="{{ old('url') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="extra" role="tabpanel" aria-labelledby="extra-tab">
                            <div class="form-group">
                                <label for="template">Шаблон</label>
                                <input type="text"
                                       id="template"
                                       name="template"
                                       value="{{ old('template') }}"
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

                        </div>
                        <div class="tab-pane fade" id="style" role="tabpanel" aria-labelledby="style-tab">
                            <div class="form-group">
                                <label for="class">Класс</label>
                                <input type="text"
                                       id="class"
                                       name="class"
                                       value="{{ old('class') }}"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="ico">Icon</label>
                                <input type="text"
                                       id="ico"
                                       name="ico"
                                       value="{{ old('ico') }}"
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
                        </div>
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
        </div>
    </div>
@endsection