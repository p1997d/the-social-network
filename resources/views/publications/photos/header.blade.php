<div class="d-flex justify-content-between gap-3 flex-lg-row flex-column">
    <div class="d-flex justify-content-between gap-2">
        <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id]) }}"
            class="btn btn-emphasis btn-sm @if (!$type) active @endif">
            Все
        </a>
        <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'type' => 'profile']) }}"
            class="btn btn-emphasis btn-sm @if ($type == 'profile') active @endif">
            Фото профиля
        </a>
        <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'type' => 'uploaded']) }}"
            class="btn btn-emphasis btn-sm @if ($type == 'uploaded') active @endif">
            Загруженные фото
        </a>
        {{-- <a class="btn btn-emphasis btn-sm">Фото на стене</a> --}}
    </div>
    <div class="d-flex justify-content-end">
        @if (auth()->user()->id == $user->id)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#uploadphoto">
                <i class="bi bi-image"></i>
                Загрузить фото
            </button>
        @endif
    </div>
</div>
