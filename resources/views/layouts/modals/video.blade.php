@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="position-absolute top-0 end-0 z-3 m-3 d-none d-lg-block">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded shadow">
            <div class="modal-header d-flex d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow: clip">
                <div class="row m-3">
                    <div class="col-9">
                        <div class="plyr-w-100 mb-3">
                            <video class="video-player w-100"></video>
                        </div>
                        <div>
                            <h5 class="titleModalVideo"></h5>
                            <span class="text-secondary fs-7 viewsModalVideo"></span>
                            <span class="separator text-secondary fs-7">•</span>
                            <span class="text-secondary fs-7 createdAtModalVideo"></span>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column gap-2">
                        @if (isset($content) && $typeContent == 'video')
                            @foreach ($content as $video)
                                <button class="btn btn-text m-0 p-0 videoCard openVideo"
                                    data-user="{{ $video->videoFile->author }}" data-video="{{ $video->id }}">
                                    <div class="card shadow">
                                        <div class="video-thumbnail position-relative">
                                            <img src="{{ asset("storage/thumbnails/$video->thumbnail") }}"
                                                class="card-img-top" alt="preview">
                                            <div class="position-absolute bottom-0 end-0">
                                                <span
                                                    class="badge text-bg-dark bg-opacity-50 m-1">{{ $video->duration }}</span>
                                            </div>
                                        </div>
                                        <div class="card-body text-start">
                                            <h5 class="card-title">{{ $video->title }}</h5>
                                            <span
                                                class="card-text text-secondary fs-7">{{ $video->viewsWithText() }}</span>
                                            <span class="separator text-secondary fs-7">•</span>
                                            <span
                                                class="card-text text-secondary fs-7">{{ Carbon::parse($video->created_at)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
