<div class="d-flex justify-content-between gap-3 flex-lg-row flex-column align-items-center">
    <div>
        @if (isset($group) && !isset($user))
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('groups.index', $group->id) }}">{{ $group->title }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Видеозаписи</li>
                </ol>
            </nav>
        @endif
    </div>
    <div>
        @if ($hasPermission)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#uploadvideo">
                <i class="bi bi-film"></i>
                Загрузить видеозапись
            </button>
        @endif
    </div>
</div>
