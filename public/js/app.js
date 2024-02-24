$(document).ready(function () {
    const menu = new bootstrap.Offcanvas("#menu");

    $('[data-bs-toggle="tooltip"]').tooltip();

    $("#avatar").hover(function () {
        $("#avatarSetting").collapse('show');
    }, function () {
        $("#avatarSetting").collapse('hide');
    });


    onResizeWindow();

    $(window).resize(function () {
        onResizeWindow();

        if ($(window).width() >= 992) {
            menu.hide();
        }
    });

    window.Echo.private(`Messages.${userId}`).listen('.message', (event) => {
        if ($('.messages-body').length) {
            changeMessagesBlock(event.data);
        }
        $.pjax.reload({ container: ".header-pjax", async: false });
        $.pjax.reload({ container: ".sidebar", async: false });
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

    const imageModal = document.getElementById('imageModal')
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const image = button.getAttribute('data-bs-image')
            $('.modal-body img').attr('src', image);
        })
    }
});

$(document).on('pjax:end', function () {
    onResizeWindow();
});

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
