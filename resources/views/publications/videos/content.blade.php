<div class="container">
    <div class="row row-cols-4 g-3" id="listVideo">
        @forelse ($videos as $i => $video)
            <div class="col-3">
                <button class="btn btn-text m-0 p-0 videoCard openVideoModal w-100" data-user="{{ $video->author }}"
                    @if (isset($group)) data-group="{{ $group->id }}" @endif
                    data-video="{{ $video->id }}">
                    <div class="card shadow">
                        <div class="video-thumbnail position-relative">
                            <img src="{{ $video->thumbnailPath }}" class="card-img-top videoThumbnail object-fit-cover"
                                alt="preview" style="width: 100%; height: 100px">
                            <div class="position-absolute bottom-0 end-0">
                                <span
                                    class="badge text-bg-dark bg-opacity-50 m-1 videoDuration">{{ $video->duration }}</span>
                            </div>
                        </div>
                        <div class="card-body text-start">
                            <h5 class="card-title videoTitle">{{ $video->title }}</h5>
                            <span class="card-text text-secondary fs-7 videoViews">{{ $video->viewsWithText() }}</span>
                            <span class="separator text-secondary fs-7">•</span>
                            <span
                                class="card-text text-secondary fs-7 videoDate">{{ $video->createdAtDiffForHumans() }}</span>
                        </div>
                    </div>
                </button>
            </div>
        @empty
            <div class="w-100 text-center emptyMessage">
                <p>{{ $emptyMessage }}</p>
            </div>
        @endforelse
    </div>
</div>
