var videoPlayer;
var videoPlaylist = [];

$(document).ready(function () {
    initializationVideoPlayer();
    initializationVideoButtons();
    showModal();
    showUploadVideoModal();
});

$(document).on('pjax:end', function () {
    initializationVideoPlayer();
    initializationVideoButtons();
    showModal();
    showUploadVideoModal();
});

function initializationVideoPlayer() {
    if (videoPlayer) {
        videoPlayer.destroy();
    }
    videoPlayer = new Plyr('.video-player');

    videoPlayer.on('ended', () => {
        let currentVideo = $(videoPlayer.media).find('source').attr('data-id');
        let user = $(videoPlayer.media).find('source').attr('data-user');

        $.ajax({
            url: '/videos/addView',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: currentVideo,
            }
        });

        let nextVideo = videoPlaylist.indexOf(Number(currentVideo)) + 1 >= videoPlaylist.length ? videoPlaylist[0] : videoPlaylist[videoPlaylist.indexOf(Number(currentVideo)) + 1];

        setUrl('video', nextVideo, user);
        getVideo(nextVideo, user);
    });
}

function showUploadVideoModal() {
    const url = new URL(window.location);
    const modal = url.searchParams.get('modal');
    if (modal) {
        $('#uploadvideo').modal('show');
    }
}

function initializationVideoButtons() {
    $('.openVideoModal').off('click keypress').on('click keypress', function () {
        openVideo(this);
    });

    $('#videoModal')
        .off('hide.bs.modal')
        .on('hide.bs.modal', () => {
            videoPlayer.pause();
            videoPlayer.source = null;
            clearUrl();
        });

    $('#formVideoUpload').off('submit').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        let number = new Date().getTime();

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#uploadvideo').modal('hide');
                showLoadingToast(number);
            },
            error: function (data) {
                showMessage({
                    color: "danger",
                    message: data.responseJSON.message
                });
            },
            success: function (data) {
                $(`.loadingToast[data-number="${number}"]`).remove();
                showMessage(data.notification);

                $('.emptyMessage').remove();

                $('<div>').attr('class', 'col')
                    .append(addVideo(data.video))
                    .appendTo('#listVideo');

                initializationVideoButtons();
            }
        });

        $(this)[0].reset();
    });
}

function getVideo(id, model) {
    $.ajax({
        url: '/videos/getVideo',
        type: 'GET',
        data: {
            id, model
        },
        success: function (data) {
            if (!data.video.deleted_at) {
                $('#videoModal').modal('show');
            } else {
                clearUrl();
                showAccessError()
                return;
            }

            let title = data.video.title;
            let type = data.video.type;
            let src = data.video.path;
            let id = data.video.id;

            videoPlayer.source = {
                type: 'video',
                title,
                sources: [{ src, type, 'data-id': id, 'data-user': data.userID }],
            };

            videoPlaylist = data.playlist.map(item => item.id);

            $('.titleModalVideo').text(data.video.title);
            $('.viewsModalVideo').text(data.viewsWithText);
            $('.createdAtModalVideo').text(data.videoModalDate);

            $('#videoModal').find('.setLike')
                .attr('data-like', data.videoModalSetLike.data)
                .find('input[name="id"]')
                .val(data.videoModalSetLike.id)
                .end()
                .find('input[name="type"]')
                .val(data.videoModalSetLike.type)
                .end()
                .find('button[type="submit"]')
                .prop('disabled', false)
                .attr('class', data.videoModalSetLike.class)
                .find('.countLikes')
                .text(data.videoModalSetLike.count)
                .end()
                .end();

            if (userId !== data.video.author) {
                $('.adminsButtons').remove();
            } else {
                $('.formDeleteVideo').find('input[name="id"]').val(data.video.id);
            }

            videoPlayer.play();

            viewPlaylist(data.playlist);
        }
    });
}

function viewPlaylist(playlist) {
    let list = $('#modalListVideo');
    list.empty();

    playlist.forEach(video => {
        addVideo(video).appendTo(list);
    });

    initializationVideoButtons();
}

function openVideo(link) {
    let group = $(link).attr('data-group');
    let [file, user] = getDataFromFile(link, 'video');
    let model = group ? `group${group}-${user}` : `user${user}`;
    setUrl('video', file, model);
    showModal();
}

function addVideo(video) {
    return $($('#video-template').html())
        .attr('data-user', video.author)
        .attr('data-video', video.id)
        .attr('data-group', video.group)
        .find('.videoThumbnail')
        .attr('src', video.thumbnailPath)
        .end()
        .find('.videoDuration')
        .text(video.duration)
        .end()
        .find('.videoTitle')
        .text(video.title)
        .end()
        .find('.videoViews')
        .text(video.viewsWithText)
        .end()
        .find('.videoDate')
        .text(video.createdAtDiffForHumans)
        .end()
}
