<div class="offcanvas offcanvas-end" tabindex="-1" id="menu" aria-labelledby="menuLabel">
    <div class="offcanvas-header align-items-baseline">
        @include('layouts.profileDroplist')
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        @include('layouts.sidebar')

        <div class="card" id="offcanvasMenuPlayer" style="display: none">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <p class="p-0 m-0 fw-bold fs-7 playerCurrentAudioTitle"></p>
                    <p class="p-0 m-0 text-secondary fs-7 playerCurrentAudioArtist"></p>
                </div>
                <div>
                    <button class="btn btn-text btn-lg p-0" id="clearPlaylist"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-emphasis btn-lg backwardButton">
                        <i class="bi bi-skip-start-fill"></i>
                    </button>
                    <button class="btn btn-emphasis btn-lg sidebarPlayButton">
                        <i class="bi bi-play-fill"></i>
                    </button>
                    <button class="btn btn-emphasis btn-lg forwardButton">
                        <i class="bi bi-skip-end-fill"></i>
                    </button>
                </div>
                <div class="progress mt-1" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                    style="height: 5px;">
                    <div class="progress-bar playerCurrentAudioProgressBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
