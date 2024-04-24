$(document).ready(function () {
    if (userId) {
        $(document).pjax('a', '#pjax-container');
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

    if (userId) {
        window.Echo.private(`Messages.${userId}`).listen('.message', (event) => {
            if ($('.messages-body').length) {
                changeMessagesBlock(event.data);
            }
            $.pjax.reload({ container: ".sidebar", async: false });
            $.pjax.reload({ container: ".header-pjax", async: false });
            notification(event);
        });

        window.Echo.private(`Friends.${userId}`).listen('.friend', (event) => {
            if ($('.friends-pjax').length) {
                $.pjax.reload({ container: ".friends-pjax", async: false });
            }
            if ($('.buttons-pjax').length) {
                $.pjax.reload({ container: ".buttons-pjax", async: false });
            }
            $.pjax.reload({ container: ".sidebar", async: false });
            notification(event);
        });
    }

});
$(document).on('pjax:clicked', function () {
    $.pjax.reload({ container: "#pjax-container", async: false });
    $.pjax.reload({ container: "#header-title-pjax", async: false });
});

$(document).on('pjax:end', function () {
    $('.modal').modal('hide')
    menu.hide();

    onResizeWindow();
    initializePageInteractions();
    $(document).scrollTop(0);
});

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
        $('.emptyToast')
            .clone()
            .appendTo('.toast-container')
            .removeClass('emptyToast')
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
    $('.generalEmptyToast')
        .clone()
        .appendTo('.toast-container')
        .removeClass('generalEmptyToast')
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

function setUrl(type, file, user, group = null) {
    const url = new URL(window.location);
    if (group) {
        url.searchParams.set('content', `${type}_${user}_${file}_${group}`);
    } else {
        url.searchParams.set('content', `${type}_${user}_${file}`);
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
    $('.emptyToast')
        .clone()
        .appendTo('.toast-container')
        .removeClass('emptyToast')
        .find('.title').text("Ошибка доступа").end()
        .find('.toast-body').remove().end()
        .toast('show')
}

function clearUrl() {
    const url = new URL(window.location);
    url.searchParams.delete('content');
    window.history.pushState({}, '', url);
}
