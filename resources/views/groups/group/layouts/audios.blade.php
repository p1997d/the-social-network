@if ($group->audios()->count() > 0 || $group->admins()->contains('id', auth()->user()->id))
    <div class="card shadow mb-3">
        <div class="card-header">
            <a href="{{ route('audios', ['group' => $group->id]) }}" class="link-body-emphasis">Аудиозаписи</a>
            <span class="text-secondary">{{ $group->audios()->count() }}</span>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse ($group->audios()->take(3) as $audio)
                    <li class="list-group-item d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-outline-primary btn-sm playAudioButton"
                            data-id="{{ $audio->id }}" data-playlist="{{ $group->playlist->id }}">
                            <i class="bi bi-play"></i>
                        </button>
                        <div class="ms-1 text-truncate">{{ $audio->artist }} -
                            {{ $audio->title }}</div>
                    </li>
                @empty
                    <a href="{{ route('audios', ['group' => $group->id, 'modal' => true]) }}"
                        class="btn btn-secondary btn-sm w-100">Добавить</a>
                @endforelse
            </ul>
        </div>
    </div>
@endif
