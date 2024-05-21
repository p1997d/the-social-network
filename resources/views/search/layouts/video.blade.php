<div class="row row-cols-4 g-3">
    @forelse ($items as $i => $video)
        <div class="col">
            <button class="btn btn-text m-0 p-0 videoCard openVideoModal" data-user="{{ $video->author }}"
                data-video="{{ $video->id }}">
                <div class="card shadow">
                    <div class="video-thumbnail position-relative">
                        <img src="{{ $video->thumbnailPath }}" class="card-img-top" alt="preview">
                        <div class="position-absolute bottom-0 end-0">
                            <span class="badge text-bg-dark bg-opacity-50 m-1">{{ $video->duration }}</span>
                        </div>
                    </div>
                    <div class="card-body text-start">
                        <h5 class="card-title">{{ $video->title }}</h5>
                        <span class="card-text text-secondary fs-7">{{ $video->viewsWithText() }}</span>
                        <span class="separator text-secondary fs-7">•</span>
                        <span class="card-text text-secondary fs-7">{{ $video->createdAtDiffForHumans() }}</span>
                    </div>
                </div>
            </button>
        </div>
    @empty
        <div class="col-12 text-center text-secondary">Ваш запрос не дал результатов</div>
    @endforelse
</div>
