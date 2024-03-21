@if ($model->avatarFile && !$model->avatarFile->deleted_at && file_exists(storage_path('app/public/files/'. $model->avatarFile->path)))
    @if ($modal)
        <div class="openImageModal" data-user="{{ $model->id }}" data-photo="{{ $model->avatarFile->id }}"
            data-type="profile" tabindex="0">
            <img src="{{ asset('storage/thumbnails/' . $model->avatarFile->path) }}" width="{{ $width }}"
                @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
        </div>
    @else
        <img src="{{ asset('storage/thumbnails/' . $model->avatarFile->path) }}" width="{{ $width }}"
            @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
    @endif
@else
    <img src="{{ $model->avatarDefault() }}" width="{{ $width }}"
        @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
@endif
