$(document).ready(function () {
    if (userId) {
        $(document).pjax('a', '#pjax-container', {
            container: '#pjax-container',
            timeout: 3000,
        });
    }
    menu = new bootstrap.Offcanvas("#menu");

    $(window).resize(function () {
        onResizeWindow();

        if ($(window).width() >= 992) {
            menu.hide();
        }
    });

    onResizeWindow();
    initializePageInteractions();
    initializeWebSockets();

});

$(document).on('pjax:end', function (event) {
    $('#header-title').text(event.currentTarget.title);

    $('.modal').modal('hide')
    menu.hide();

    onResizeWindow();
    initializePageInteractions();
    $(document).scrollTop(0);
});

function initializeWebSockets() {
    if (userId) {
        window.Echo.private(`Messages.${userId}`).listen('.message', (event) => {
            if ($('.messages-body').length) {
                changeMessagesBlock(event.data);
            }
            if ($('.messages-pjax').length) {
                $.pjax.reload({ container: ".messages-pjax", async: false });
            }
            notification(event);
            updateCounters();
        });

        window.Echo.private(`Friends.${userId}`).listen('.friend', (event) => {
            if ($('.buttons-pjax').length) {
                $.pjax.reload({ container: ".buttons-pjax", async: false });
            }
            notification(event);
            updateCounters();
        });
    }
}

function updateCounters() {
    $.ajax({
        url: '/getCounters',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('.messagesCounter').text(data.unreadMessagesCount);
            $('.friendsCounter').text(data.incomingCount);
        }
    });
}

function initializePageInteractions() {
    $('[data-bs-toggle="tooltip"]').tooltip();

    $("#avatar").hover(function () {
        $("#avatarSetting").collapse('show');
    }, function () {
        $("#avatarSetting").collapse('hide');
    });
}

function onResizeWindow() {
    var dynamicChildWidth = $('.messages').outerWidth();
    $('.messages-header, .position-fixed').css('width', dynamicChildWidth);
}

function notification(event) {
    if (event.notification) {
        $($('#toast-template').html())
            .appendTo('.toast-container')
            .find('.title').text(event.title).end()
            .find('.subtitle').text(event.subtitle).end()
            .find('.description').text(event.description).end()
            .find('.link').attr('href', event.link).end()
            .find('.image').attr("src", event.image.thumbnailPath).end()
            .toast('show')

        playNotificationAudio();
    }
}

function playNotificationAudio() {
    const audio = new Audio('../mp3/notification.mp3');
    audio.play();
}

function showMessage(data) {
    $($('#generaltoast-template').html())
        .appendTo('.toast-container')
        .addClass(`text-bg-${data.color}`)
        .find('.toast-body')
        .text(data.message)
        .end()
        .toast('show');
}

function getDataFromFile(item, type) {
    const file = $(item).attr(`data-${type}`);
    const user = $(item).attr('data-user');
    const group = $(item).attr('data-group');

    return [file, user, group];
}

function setUrl(type, file, model, group = null) {
    const url = new URL(window.location);
    if (group) {
        url.searchParams.set('content', `${type}_${model}_${file}_${group}`);
    } else {
        url.searchParams.set('content', `${type}_${model}_${file}`);
    }
    window.history.pushState({}, '', url);

    return file;
}

function showModal() {
    const urlParams = new URLSearchParams(window.location.search);
    const content = urlParams.get('content');

    if (content) {
        const type = content.split('_')[0];
        const user = content.split('_')[1];
        const file = content.split('_')[2];

        switch (type) {
            case 'photo':
                getPhoto(file);
                break;
            case 'video':
                getVideo(file, user);
                break;
        }
    }
}

function showAccessError() {
    $($('#toast-template').html())
        .appendTo('.toast-container')
        .find('.title').text("Ошибка доступа").end()
        .find('.toast-body').remove().end()
        .toast('show')
}

function clearUrl() {
    const url = new URL(window.location);
    url.searchParams.delete('content');
    window.history.pushState({}, '', url);
}

function getAttachments(attachments) {
    let $row = $('<div>').addClass(`row row-cols-5 g-2 my-2`);

    attachments.forEach(item => {
        switch (item.type.split('/')[0]) {
            case 'image':
                $col = $('<div>').addClass('col').appendTo($row);

                $div = $('<div>')
                    .addClass('openImageModal')
                    .attr('data-user', item.author)
                    .attr('data-photo', item.id)
                    .attr('data-group', 'messages')
                    .attr('tabindex', '0')
                    .appendTo($col);

                $img = $('<img>')
                    .attr('src', item.thumbnailPath)
                    .addClass('photos rounded')
                    .appendTo($div);

                break;

            case 'audio':
                $col = $('<div>').addClass('col-12').appendTo($row);
                $audio = $('<audio>').addClass('player').attr('controls', true).appendTo($col);
                $source = $('<source>').attr('src', item.path).attr('type', item.type).appendTo($audio);

                players.push(new Plyr($audio));
                break;

            case 'video':
                $col = $('<div>').addClass('col-12').appendTo($row);
                $video = $('<video>').addClass('player').attr('controls', true).appendTo($col);
                $source = $('<source>').attr('src', item.path).attr('type', item.type).appendTo($video);

                players.push(new Plyr($video));
                break;

            default:
                $col = $('<div>').addClass('col-auto').appendTo($row);
                $a = $('<a>')
                    .attr('href', item.path)
                    .attr('target', '_blank')
                    .addClass('link-underline link-underline-opacity-0')
                    .appendTo($col);
                $card = $('<div>').addClass('card').appendTo($a);
                $cardBody = $('<div>')
                    .addClass('card-body d-flex justify-content-start gap-2')
                    .append('<i class="bi bi-file-earmark"></i>')
                    .append(`<div> ${item.name} </div>`)
                    .append(`<div class="text-secondary"> ${getSize(item.size)} </div>`)
                    .appendTo($card);
                break;
        }
    });

    return $row;
}

function showLoadingToast(number) {
    $($('#generaltoast-template').html())
        .appendTo('.toast-container')
        .addClass('loadingToast')
        .attr('data-number', number)
        .find('.btn-close').remove().end()
        .find('.toast-body')
        .addClass('w-100')
        .append(
            $('<h3>')
            .text('Загрузка...')
        )
        .append(
            $('<div>')
                .attr('class', 'progress')
                .attr('role', 'progressbar')
                .append(
                    $('<div>')
                        .attr('class', 'progress-bar progress-bar-striped progress-bar-animated')
                        .css('width', '100%')
                )
        )
        .end()
        .toast('show');
}
