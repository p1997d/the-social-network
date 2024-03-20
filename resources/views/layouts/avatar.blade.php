@if ($model->avatarFile && !$model->avatarFile->deleted_at)
    @if ($modal)
        <div class="openImageModal" data-user="{{ $model->id }}" data-photo="{{ $model->avatarFile->id }}"
            data-type="profile" tabindex="0">
            <img src="{{ asset('storage/files/' . $model->avatarFile->path) }}" width="{{ $width }}"
                @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
        </div>
    @else
        <img src="{{ asset('storage/files/' . $model->avatarFile->path) }}" width="{{ $width }}"
            @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
    @endif
@else
    <img src="{{ $model->avatarDefault() }}" width="{{ $width }}"
        @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
@endif