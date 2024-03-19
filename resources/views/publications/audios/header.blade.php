<div class="d-flex justify-content-between">
    <div>{{-- кнопки --}}</div>
    <div>
        @if (auth()->user()->id == $user->id)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#uploadaudio">
                <i class="bi bi-music-note"></i>
                Загрузить аудиозапись
            </button>
        @endif
    </div>
</div>
