<ul class="list-group list-group-flush" id="listAudio"
    @isset($group) data-owner="group{{ $group->id }}" @else data-owner="user{{ $user->id }}" @endif>
    @forelse ($audios as $i => $audio)
        <li
            class="list-group-item d-flex justify-content-between align-items-start align-items-lg-center mt-2 flex-column flex-lg-row w-100 gap-2">
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-outline-primary btn-sm playAudioButton" data-id="{{ $audio->id }}"
                    data-playlist="{{ $playlist->id }}">
                    <i class="bi bi-play"></i>
                </button>
                @if ((auth()->user()->id !== $audio->author && (isset($user) && auth()->user()->id !== $user->id)) || isset($group))
                    <button class="btn btn-outline-primary btn-sm addAudioButton" data-audio="{{ $audio->id }}">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                @endif
                <a href="{{ route('audios.download', $audio->id) }}"
                    class="btn btn-outline-primary btn-sm audioDownload">
                    <i class="bi bi-download"></i>
                </a>
                @if ($hasPermission)
                    <button class="btn btn-outline-secondary btn-sm deleteAudioButton" data-audio="{{ $audio->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
    </div>
    <div class="flex-grow-1 audioTitle">{{ $audio->artist }} - {{ $audio->title }}</div>
    <div class="text-secondary audioDuration">
        {{ $audio->duration }}
    </div>
    </li>
    @empty
        <div class="w-100 text-center emptyMessage">
            <p>{{ $emptyMessage }}</p>
        </div>
        @endforelse
    </ul>
