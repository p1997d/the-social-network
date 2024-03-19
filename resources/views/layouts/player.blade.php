<div class="dropdown-center d-lg-block d-none" id="playerDropdown">
    <button class="btn btn-emphasis openPlayerButton text-truncate" type="button" data-bs-toggle="dropdown"
        aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-music-note-beamed"></i>
    </button>
    <div class="position-absolute translate-middle top-50 start-0 headerPlayerButtons" style="display: none">
        <button class="btn btn-emphasis btn-sm position-absolute translate-middle backwardButton"
            style="top:50%; left:20px;">
            <i class="bi bi-skip-start-fill"></i>
        </button>
        <button class="btn btn-emphasis btn-sm position-absolute translate-middle headerPlayButton"
            style="top:50%; left:55px;">
            <i class="bi bi-play-fill"></i>
        </button>
        <button class="btn btn-emphasis btn-sm position-absolute translate-middle forwardButton"
            style="top:50%; left:90px;">
            <i class="bi bi-skip-end-fill"></i>
        </button>
    </div>
    <div class="dropdown-menu p-0 m-0">
        <div class="card shadow" id="playerCard" style="width: 700px">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-text btn-lg p-1 backwardButton">
                            <i class="bi bi-skip-start-fill"></i>
                        </button>
                        <button class="btn btn-text btn-lg p-1 playerPlayButton">
                            <i class="bi bi-play-circle-fill"></i>
                        </button>
                        <button class="btn btn-text btn-lg p-1 forwardButton">
                            <i class="bi bi-skip-end-fill"></i>
                        </button>
                    </div>
                    <div class="col-auto flex-grow-1">
                        <div>
                            <p class="p-0 m-0 fw-bold fs-7 playerCurrentAudioTitle"></p>
                            <p class="p-0 m-0 text-secondary fs-7 playerCurrentAudioArtist"></p>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-auto flex-grow-1">
                                <div class="progress mt-1" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                    style="height: 5px;">
                                    <div class="progress-bar playerCurrentAudioProgressBar" style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <p class="p-0 m-0 text-secondary fs-7 playerCurrentAudioDuration">0:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <input type="range" class="form-range" id="playerVolumeRange"
                            style="width: 100px; height: 5px;">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link link-body-emphasis selectPlaylistButton active" aria-current="page"
                            data-playlist="myPlaylist">
                            Мои аудиозаписи
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link link-body-emphasis selectPlaylistButton"
                            data-playlist="currentPlaylist">
                            Текущий список воспроизведения
                        </button>
                    </li>
                </ul>
                <ul class="list-group list-group-flush mt-3 overflow-y-auto" id="forPlaylist"
                    style="max-height: 450px;"></ul>
            </div>
        </div>
    </div>
    <audio class="main-player"></audio>
</div>
