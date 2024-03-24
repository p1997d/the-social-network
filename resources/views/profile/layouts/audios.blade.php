@auth
    @if ($audios->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <a href="{{ route('audios', ['id' => $user_profile->id]) }}" class="link-body-emphasis">Аудиозаписи</a>
                <span class="text-secondary">{{ $audios->count() }}</span>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach ($audios->take(3) as $audio)
                        <li class="list-group-item d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm playAudioButton"
                                data-id="{{ $audio->id }}" data-playlist="{{ $playlist->id }}">
                                <i class="bi bi-play"></i>
                            </button>
                            <div class="ms-1 text-truncate">{{ $audio->artist }} -
                                {{ $audio->title }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endauth
