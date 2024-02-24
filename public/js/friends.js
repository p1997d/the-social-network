$(document).ready(function () {
    start();
});

$(document).on('pjax:end', function () {
    start();
});

function start() {
    $('.formFriends').off('submit');

    $('.formFriends').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function () {
                if ($('.friends-pjax').length) {
                    $.pjax.reload({ container: ".friends-pjax", async: false });
                }
                if ($('.buttons-pjax').length) {
                    $.pjax.reload({ container: ".buttons-pjax", async: false });
                }
                $.pjax.reload({ container: ".sidebar", async: false });
            }
        });
        $(this)[0].reset();
    })
}
