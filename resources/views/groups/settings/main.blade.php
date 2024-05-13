@extends('groups.settings.layouts.index')

@section('groupSettingsTitle', 'Основная информация')

@section('groupSettingsBody')
    <form action="{{ route('groups.update', $group->id) }}" method="post">
        @csrf
        <div class="row mb-3">
            <label for="titleInput" class="col-sm-3 col-form-label text-secondary">Название группы</label>
            <div class="col-sm-9">
                <input value="{{ $group->title }}" class="form-control" type="text" id="titleInput" name="title"
                    placeholder="Введите название" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="themeInput" class="col-sm-3 col-form-label text-secondary">Тематика группы</label>
            <div class="col-sm-9">
                <input value="{{ $group->theme }}" class="form-control" type="text" id="themeInput" name="theme"
                    placeholder="Введите тему" required>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
