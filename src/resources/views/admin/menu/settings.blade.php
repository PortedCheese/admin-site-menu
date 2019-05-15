@extends('admin.layout')

@section('page-title', 'Настройки меню - ')
@section('header-title', 'Настройки меню')

@section('admin')
    <div class="col-12">
        <form action="{{ route('admin.settings.menus.save') }}"
              method="post">
            @csrf
            @method('put')

            <div class="form-check">
                <input type="checkbox"
                       @if($config->useOwnAdminRoutes)
                       checked
                       @endif
                       class="form-check-input"
                       value=""
                       name="own-admin"
                       id="own-admin">
                <label for="own-admin">
                    Собственные адреса для админки
                </label>
            </div>

            <div class="btn-group mt-2"
                 role="group">
                <button type="submit" class="btn btn-success">
                    Обновить
                </button>
            </div>

        </form>
    </div>
@endsection
