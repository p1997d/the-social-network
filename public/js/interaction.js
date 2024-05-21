$(document).ready(function () {
    initializationInteraction();
});

$(document).on('pjax:end', function () {
    initializationInteraction();
});

function initializationInteraction() {
    $('.setLike').off('submit').on('submit', function (event) {
        let form = $(this);
        event.preventDefault();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                form.children('button[type="submit"]').attr("class", data.class);
                form.children('button[type="submit"]').children('.countLikes').text(data.countLikes);
            },
        });
    });
}
