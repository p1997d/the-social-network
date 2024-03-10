$(document).ready(function () {
    $(document).pjax('a', '#pjax-container');

    menu = new bootstrap.Offcanvas("#menu");

    $(window).resize(function () {
        onResizeWindow();

        if ($(window).width() >= 992) {
            menu.hide();
        }
    });

    onResizeWindow();
    initializePageInteractions();

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

});

$(document).on('pjax:end', function () {
    $('.modal').modal('hide')
    menu.hide();

    onResizeWindow();
    initializePageInteractions();
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
            .find('.image').attr("src", event.image).end()
            .toast('show')

        playNotificationAudio();
    }
}

function playNotificationAudio() {
    const audio = new Audio('../mp3/notification.mp3');
    audio.play();
}
