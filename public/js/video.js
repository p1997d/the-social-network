var videoPlayer;
var videoPlaylist = [];

$(document).ready(function () {
    initializationVideo();
});

$(document).on('pjax:end', function () {
    initializationVideo();
});

function initializationVideo() {
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

    $('.openVideoModal')
        .off('click keypress')
        .on('click keypress', function () {
            const [file, user] = getDataFromFile(this, 'video');
            setUrl('video', file, user);

            $('#videoModal').modal('show');
        });

    $('.openVideo')
        .off('click')
        .on('click', function () {
            const [file, user] = getDataFromFile(this, 'video');
            setUrl('video', file, user);
            getVideo(file, user);
        })

    $('#videoModal')
        .off('hide.bs.modal')
        .on('hide.bs.modal', () => {
            videoPlayer.pause();
            clearUrl();
        });

    $('#videoModal')
        .off('show.bs.modal')
        .on('show.bs.modal', () => {
            $.pjax.reload({ container: "#videoModal", async: false });
        });



    $('#formVideoUpload').off('submit').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#uploadvideo').modal('hide');
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

    showModal();
}

function getVideo(file, user) {
    $.ajax({
        url: '/videos/getVideo',
        type: 'GET',
        data: {
            id: file,
            user,
        },
        success: function (data) {
            if (!data.video.deleted_at) {
                $('#videoModal').modal('show');
            } else {
                clearUrl();
                showAccessError()
            }

            let title = data.video.title;
            let type = data.video.type;
            let src = data.path;
            let id = data.video.id;

            videoPlayer.source = {
                type: 'video',
                title,
                sources: [{ src, type, 'data-id': id, 'data-user': data.userID }],
            };

            videoPlaylist = data.playlist;

            $('.titleModalVideo').text(data.video.title);
            $('.viewsModalVideo').text(data.viewsWithText);
            $('.createdAtModalVideo').text(data.createdAt);

            videoPlayer.play();
        }
    });
}
