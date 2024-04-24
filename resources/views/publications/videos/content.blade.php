@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
@endphp

<div class="container">
    <div class="row row-cols-4 g-3">
        @forelse ($videos as $i => $video)
            <div class="col">
                <button class="btn btn-text m-0 p-0 videoCard openVideoModal" data-user="{{ $video->author }}"
                    data-video="{{ $video->id }}">
                    <div class="card shadow">
                        <div class="video-thumbnail position-relative">
                            <img src="{{ $video->thumbnailPath }}" class="card-img-top"
                                alt="preview">
                            <div class="position-absolute bottom-0 end-0">
                                <span class="badge text-bg-dark bg-opacity-50 m-1">{{ $video->duration }}</span>
                            </div>
                        </div>
                        <div class="card-body text-start">
                            <h5 class="card-title">{{ $video->title }}</h5>
                            <span class="card-text text-secondary fs-7">{{ $video->viewsWithText() }}</span>
                            <span class="separator text-secondary fs-7">•</span>
                            <span
                                class="card-text text-secondary fs-7">{{ Carbon::parse($video->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </button>
            </div>
        @empty
            <div class="w-100 text-center">
                @if (auth()->user()->id == $user->id)
                    <p>Вы ещё не загружали видеозаписи</p>
                @else
                    <p>{{ $user->firstname }} ещё не
                        добавил{{ $user->sex == 'female' ? 'а' : '' }}
                        видеозаписи
                    </p>
                @endif
            </div>
        @endforelse
    </div>
</div>
