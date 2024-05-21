
@auth
    @if ($user->videos->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <a href="{{ route('videos', ['id' => $user->id]) }}" class="link-body-emphasis">Видеозаписи</a>
                <span class="text-secondary">{{ $user->videos->count() }}</span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach ($user->videos->take(2) as $video)
                        <div class="col-12">
                            <button class="btn btn-text m-0 p-0 videoCard openVideoModal w-100"
                                data-user="{{ $video->author }}" data-video="{{ $video->id }}">
                                <div class="card shadow">
                                    <div class="video-thumbnail position-relative">
                                        <img src="{{ $video->thumbnailPath }}" class="card-img-top object-fit-cover"
                                            alt="preview" style="width: 100%; height: 150px">
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
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endauth
