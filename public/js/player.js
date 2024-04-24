var mainPlayer;
var players = [];
var currentPlaylist = [];

$(document).ready(function () {
    mainPlayer = new Plyr('.main-player', {
        controls: false,
    });

    initializationPlayers();
    initializationPlayerButton();
    getLastAudio();
});

$(document).on('pjax:end', function () {
    initializationPlayers();
    initializationPlayerButton();
    setPlayIcon()
});


function initializationPlayers() {
    players = Plyr.setup('.player');

    if (players === null) {
        players = [];
    }

    if (players && players.length > 0) {
        players.forEach(function (instance) {
            instance.on('play', function () {
                players.forEach(function (instance1) {
                    if (instance !== instance1) {
                        instance1.pause();
                    }
                });
                mainPlayer.pause();
            });

            mainPlayer.on('play', function () {
                instance.pause();
            });
        });
    }


    $('#playerVolumeRange').val(mainPlayer.volume * 100);

    mainPlayer.on('play pause', () => {
        setPlayIcon();
    });

    mainPlayer.on('timeupdate', () => {
        const currentTime = mainPlayer.currentTime;
        const duration = mainPlayer.duration;
        const seekPercentage = (currentTime / duration) * 100;

        $('.playerCurrentAudioProgressBar').css('width', seekPercentage + '%');
    });

    mainPlayer.on('ended', () => {
        forwardAudio();
    });
}

function initializationPlayerButton() {
    $('.playAudioButton').off('click').on('click', function () {
        let button = $(this);

        let currentTrack = getCurrentTrack();

        if (button.attr('data-id') !== currentTrack) {
            let id = button.attr('data-id');
            let playlist = button.attr('data-playlist');

            playAudio(id, playlist);
        } else {
            mainPlayer.togglePlay();
        }
    });

    $('.forwardButton').off('click').on('click', function () {
        forwardAudio();
    });

    $('.backwardButton').off('click').on('click', function () {
        backwardAudio();
    });


    $('.headerPlayButton, .playerPlayButton, .sidebarPlayButton').off('click').on('click', function () {
        let currentTrack = getCurrentTrack();

        if (currentTrack) {
            mainPlayer.togglePlay();
        }
    });

    $('#playerVolumeRange').off('input').on('input', function () {
        mainPlayer.volume = $(this).val() / 100;
    });

    $('#playerDropdown').off('show.bs.dropdown').on('show.bs.dropdown', function () {
        let activePlaylist = $('.selectPlaylistButton.active').attr('data-playlist');
        selectPlaylist(activePlaylist);
    });

    $('.selectPlaylistButton').off('click').on('click', function () {
        let button = $(this);
        let activePlaylist = button.attr('data-playlist');

        selectPlaylist(activePlaylist);

        $('.selectPlaylistButton').removeClass('active');
        $(`.selectPlaylistButton[data-playlist=${activePlaylist}]`).addClass('active');
    });

    $('#clearPlaylist').off('click').on('click', function () {
        $.ajax({
            url: '/audios/clearPlaylist',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                let activePlaylist = $('.selectPlaylistButton.active').attr('data-playlist');
                selectPlaylist(activePlaylist);
                $('.playerCurrentAudioArtist').text("");
                $('.playerCurrentAudioTitle').text("");
                $('.playerCurrentAudioDuration').text("");
                $('.openPlayerButton').html('<i class="bi bi-music-note-beamed"></i>');
                $('.headerPlayerButtons').hide();
                $('#offcanvasMenuPlayer').hide();

                mainPlayer.stop();

                $(mainPlayer.media).find('source').remove();
                $('#playerCard .card-footer').remove();
            }
        });
    });

    $('.addAudioButton').off('click').on('click', function () {
        let button = $(this);
        let audio = button.attr('data-audio');

        $.ajax({
            url: '/audios/add',
            type: 'POST',
            data: { audio },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $.pjax.reload({ container: "#pjax-container", async: false });
                showMessage(data);
            }
        });
    });

    $('.deleteAudioButton').off('click').on('click', function () {
        let button = $(this);
        let audio = button.attr('data-audio');

        $.ajax({
            url: '/audios/delete',
            type: 'POST',
            data: { audio },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $.pjax.reload({ container: "#pjax-container", async: false });
                showMessage(data);
            }
        });
    });

    $('#formAudioUpload').off('submit').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#uploadaudio').modal('hide');
            },
            error: function (data) {
                showMessage({
                    color: "danger",
                    message: data.responseJSON.message
                });
            },
            success: function (data) {
                $.pjax.reload({ container: "#pjax-container", async: false });
                showMessage(data);
            }
        });

        $(this)[0].reset();
    });
}

function togglePlayPauseClasses(element, state) {
    let invertState = state == 'pause' ? 'play' : 'pause';

    button = $(element).find(`[class*="bi-${invertState}"]`);

    if (button.length) {
        let regexp = new RegExp(`(^|\\s)bi-${invertState}[^\\s]*`, 'g');

        let oldClass = button.attr('class').match(regexp).join(' ');
        let newClass = oldClass.replace(invertState, state);

        button.removeClass(oldClass).addClass(newClass);
    }
}

function playAudio(id, playlist = null) {
    $.ajax({
        url: '/audios/getAudio',
        type: 'GET',
        data: {
            id, playlist,
        },
        success: function (data) {
            if (data.playlist) {
                currentPlaylist = data.playlist;
            }

            setAudioData(data);

            mainPlayer.togglePlay();
        }
    });
}

function forwardAudio() {
    let currentTrack = getCurrentTrack();

    if (currentPlaylist.indexOf(Number(currentTrack)) !== -1) {
        if (currentPlaylist.indexOf(Number(currentTrack)) + 1 < currentPlaylist.length) {
            let id = currentPlaylist[currentPlaylist.indexOf(Number(currentTrack)) + 1];
            playAudio(id);
        } else {
            let id = currentPlaylist[0];
            playAudio(id);
        }
    }
}

function backwardAudio() {
    let currentTrack = getCurrentTrack();

    if (currentPlaylist.indexOf(Number(currentTrack)) !== -1) {
        if (currentPlaylist.indexOf(Number(currentTrack)) - 1 >= 0) {
            let id = currentPlaylist[currentPlaylist.indexOf(Number(currentTrack)) - 1];
            playAudio(id);
        } else {
            let id = currentPlaylist[currentPlaylist.length - 1];
            playAudio(id);
        }
    }
}

function getCurrentTrack() {
    let currentTrack = $(mainPlayer.media)
        .find('source')
        .attr('data-id');

    return currentTrack;
}

function selectPlaylist(activePlaylist) {
    let forPlaylist = $('#forPlaylist');
    let currentTrack = getCurrentTrack();

    $.ajax({
        url: '/audios/getPlaylist',
        type: 'GET',
        data: {
            playlist: activePlaylist,
        },
        beforeSend: function () {
            forPlaylist.html(`
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status"></div>
                </div>
            `)
        },
        success: function (data) {
            console.log(data)
            forPlaylist.html('');
            if (data.audios && data.audios.length > 0) {
                data.audios.forEach(item => {
                    li = $('<li>')
                        .addClass('list-group-item d-flex align-items-center justify-content-between gap-1 px-0')
                        .appendTo(forPlaylist);

                    leftDiv = $('<div>').addClass('d-flex align-items-center gap-1 px-0').appendTo(li);
                    rightDiv = $('<div>').addClass('d-flex align-items-center gap-1 px-0').appendTo(li);

                    playButton = $('<button>')
                        .addClass('btn btn-outline-primary btn-sm playAudioButton')
                        .attr('type', 'button')
                        .attr('data-id', item.id)
                        .attr('data-playlist', item.pivot.playlist)
                        .html(mainPlayer.playing && item.id == currentTrack ? '<i class="bi bi-pause"></i>' : '<i class="bi bi-play"></i>')
                        .appendTo(leftDiv);

                    if (item.author !== userId && data.owner.id !== userId) {
                        addButton = $('<button>')
                            .addClass('btn btn-outline-primary btn-sm addAudioButton')
                            .attr('type', 'button')
                            .attr('data-audio', item.id)
                            .html('<i class="bi bi-plus-lg"></i>')
                            .appendTo(leftDiv);
                    }

                    title = $('<div>')
                        .addClass('ms-1 text-truncate')
                        .text(`${item.artist} - ${item.title}`)
                        .appendTo(leftDiv);

                    duration = $('<div>')
                        .addClass('text-secondary')
                        .html(item.duration)
                        .appendTo(rightDiv);
                });

                initializationPlayerButton();
            } else {
                forPlaylist.html('<div class="w-100 text-center"><p>Этот плейлист пуст</p></div>');
            }

        }
    });
}

function setAudioData(data) {
    let currentTrack = getCurrentTrack();

    let duration = data.audio.duration;
    let artist = data.audio.artist;
    let title = data.audio.title;
    let type = data.type;
    let src = data.path;
    let id = data.audio.id;

    let fullTitle = `${artist} - ${title}`;

    if (id !== currentTrack) {
        mainPlayer.source = {
            type: 'audio',
            title: fullTitle,
            sources: [{ src, type, 'data-id': id, }],
        };

        $('.playerCurrentAudioArtist').text(artist);
        $('.playerCurrentAudioTitle').text(title);
        $('.playerCurrentAudioDuration').text(duration);

        $('.openPlayerButton').html($('<span>').addClass('text-secondary').css({ "padding-left": "100px" }).text(fullTitle));
        $('.headerPlayerButtons').show();
        $('#offcanvasMenuPlayer').show();
    }

    $('#playerCard .card-footer').remove();

    footer = $('<div>')
        .addClass('card-footer p-0 d-flex justify-content-end');
    button = $('<button>')
        .addClass('btn btn-link')
        .attr('id', 'clearPlaylist')
        .text('Очистить плейлист')
        .appendTo(footer);

    $('#playerCard').append(footer);

    initializationPlayerButton();
}

function getLastAudio() {
    $.ajax({
        url: '/audios/getLastAudio',
        type: 'GET',
        success: function (data) {
            if (data) {
                currentPlaylist = data.playlist;
                setAudioData(data);
            }
        }
    });
}

function setPlayIcon() {
    let currentTrack = getCurrentTrack();

    if (mainPlayer.playing) {
        togglePlayPauseClasses('.headerPlayButton', 'pause');
        togglePlayPauseClasses('.playerPlayButton', 'pause');
        togglePlayPauseClasses('.sidebarPlayButton', 'pause');

        togglePlayPauseClasses('.playAudioButton', 'play');
        togglePlayPauseClasses(`.playAudioButton[data-id="${currentTrack}"]`, 'pause');
    } else {
        togglePlayPauseClasses('.headerPlayButton', 'play');
        togglePlayPauseClasses('.playerPlayButton', 'play');
        togglePlayPauseClasses('.sidebarPlayButton', 'play');
        togglePlayPauseClasses('.playAudioButton', 'play');
    }
}
