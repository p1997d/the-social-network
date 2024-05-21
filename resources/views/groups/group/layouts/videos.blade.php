@if ($group->videos->count() > 0 || $group->admins()->contains('id', auth()->user()->id))
    <div class="card shadow">
        <div class="card-header">
            <a href="{{ route('videos', ['group' => $group->id]) }}" class="link-body-emphasis">Видеозаписи</a>
            <span class="text-secondary">{{ $group->videos->count() }}</span>
        </div>
        <div class="card-body">
            <div class="row g-2">
                @forelse ($group->videos->take(2) as $video)
                    <div class="col-12">
                        <button class="btn btn-text m-0 p-0 videoCard openVideoModal" data-user="{{ $video->author }}"
                            data-group="{{ $group->id }}" data-video="{{ $video->id }}">
                            <div class="card shadow">
                                <div class="video-thumbnail position-relative">
                                    <img src="{{ $video->thumbnailPath }}" class="card-img-top" alt="preview">
                                    <div class="position-absolute bottom-0 end-0">
                                        <span class="badge text-bg-dark bg-opacity-50 m-1">{{ $video->duration }}</span>
                                    </div>
                                </div>
                                <div class="card-body text-start">
                                    <h5 class="card-title">{{ $video->title }}</h5>
                                </div>
                            </div>
                        </button>
                    </div>
                @empty
                    <a href="{{ route('videos', ['group' => $group->id, 'modal' => true]) }}"
                        class="btn btn-secondary btn-sm w-100">Добавить</a>
                @endforelse
            </div>
        </div>
    </div>
@endif
