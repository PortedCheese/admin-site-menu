@if ($errors->has('title'))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ $errors->first('title') }}
    </div>
@endif
@if ($errors->has('key'))
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ $errors->first('key') }}
    </div>
@endif

<form action="{{ route('admin.menus.store') }}"
      method="post">
    @csrf
    <div class="form-row align-items-baseline">
        <div class="col-auto">
            <label for="title" class="sr-only">Название</label>
            <div class="input-group mb-2">
                <input type="text"
                       value="{{ old('title') }}"
                       class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                       name="title"
                       id="title"
                       placeholder="Название">
            </div>
        </div>

        <div class="col-auto">
            <label for="key" class="sr-only">Ключ</label>
            <div class="input-group mb-2">
                <input type="text"
                       value="{{ old('key') }}"
                       class="form-control{{ $errors->has('key') ? ' is-invalid' : '' }}"
                       name="key"
                       id="key"
                       placeholder="Ключ">
            </div>
        </div>

        <div class="col-auto">
            <button type="submit"
                    class="btn btn-success mb-2">
                Добавить
            </button>
        </div>
    </div>
</form>