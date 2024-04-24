@if (!$model->avatar()->default)
    @if ($modal)
        <div class="openImageModal" data-user="{{ $model->id }}" data-photo="{{ $model->avatarFile->id }}"
            data-group="profile" tabindex="0">
            <img src="{{ $model->avatar()->thumbnailPath }}" width="{{ $width }}"
                @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
        </div>
    @else
        <img src="{{ $model->avatar()->thumbnailPath }}" width="{{ $width }}"
            @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
    @endif
@else
    <img src="{{ $model->avatar()->path }}" width="{{ $width }}"
        @if (isset($height)) height="{{ $height }}" @endif class="{{ $class }}" />
@endif
