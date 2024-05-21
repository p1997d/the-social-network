<div class="d-flex justify-content-between gap-3 flex-lg-row flex-column align-items-center">
    @if (!isset($group) && isset($user))
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
            <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'type' => 'wall']) }}"
                class="btn btn-emphasis btn-sm @if ($type == 'wall') active @endif">
                Фото на стене
            </a>
        </div>
    @else
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('groups.index', $group->id) }}">{{ $group->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Фотографии</li>
            </ol>
        </nav>
    @endif
    <div class="d-flex justify-content-end">
        @if ($hasPermission)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#uploadphoto">
                <i class="bi bi-image"></i>
                Загрузить фото
            </button>
        @endif
    </div>
</div>
