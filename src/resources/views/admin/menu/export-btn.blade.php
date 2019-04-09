<div class="d-none">
    <form action="{{ route('admin.menus.export') }}" method="post" id="load-structure">
        @csrf
        <div class="form-row align-items-center">
            <div class="col-auto">
                <button type="submit"
                        class="btn btn-success mb-2">
                    Скачать структуру
                </button>
            </div>
        </div>
    </form>
</div>
<div class="col-12 mb-2">
    <form action="{{ route('admin.menus.import') }}"
          enctype="multipart/form-data"
          method="post">
        @csrf
        <div class="form-group">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                        <span class="input-group-text"
                              id="inputGroupAvatar">
                            Файл
                        </span>
                </div>
                <div class="custom-file">
                    <input type="file"
                           class="custom-file-input"
                           id="custom-file-input"
                           lang="ru"
                           name="file"
                           aria-describedby="inputGroupAvatar">
                    <label class="custom-file-label"
                           for="custom-file-input">
                        Выберите файл
                    </label>
                    @if ($errors->has('file'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="btn-group"
             role="group">
            <button type="submit"
                    class="btn btn-danger">
                Загрузить
            </button>
            <button type="button"
                    onclick="event.preventDefault();document.getElementById('load-structure').submit();"
                    class="btn btn-success">
                Скачать структуру
            </button>
        </div>
    </form>
</div>