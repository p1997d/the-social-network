<div class="d-flex justify-content-end">
    <div>
        @if (auth()->user()->id == $user->id)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#uploadvideo">
                <i class="bi bi-film"></i>
                Загрузить видеозапись
            </button>
        @endif
    </div>
</div>
