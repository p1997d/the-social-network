<div class="card shadow mb-3">
    <div class="card-header d-flex justify-content-between">
        <button class="btn btn-emphasis"><i class="bi bi-image"></i> Фото</button>
        <button class="btn btn-emphasis"><i class="bi bi-film"></i> Видео</button>
        <button class="btn btn-emphasis"><i class="bi bi-music-note-beamed"></i> Музыка</button>
        <button class="btn btn-emphasis"><i class="bi bi-chat-left"></i> Обсуждения</button>
        <button class="btn btn-emphasis"><i class="bi bi-file-earmark"></i> Файлы</button>
    </div>
    <div class="card-body py-3 row text-center g-3">

        {{-- @forelse ($publications as $publication) --}}
            {{-- <div class="col"><img src="https://placehold.co/128x128"></div>--}}
        {{-- @empty --}}
            <div class="col-12 py-5">
                <p>Пока никто не добавил фото</p>
                @if ($group->admins()->contains('id', auth()->user()->id))
                    <button class="btn btn-secondary">Добавить</button>
                @endif
            </div>
        {{-- @endforelse --}}
    </div>
    <div class="card-footer">
        <button class="btn btn-emphasis w-100">Показать все</button>
    </div>
</div>
