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

    $('#shareModal').off('show.bs.modal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.attr('data-bs-id');
        const type = button.attr('data-bs-type');

        $('#shareModal').find('input[name="id"]').attr('value', id);
        $('#shareModal').find('input[name="type"]').attr('value', type);
    });

    $('#selectShareInMessage, #selectShareInGroup').select2({
        language: "ru",
        theme: 'bootstrap-5',
        dropdownParent: $("#shareModal"),
    });

    $('input[name="radioShare"]').off('change').on('change', function () {
        $('.selectShareInGroupDiv, .selectShareInMessageDiv').addClass('d-none');
        $('#selectShareInGroup, #selectShareInMessage').attr('required', false);

        switch ($(this).val()) {
            case 'group':
                $('.selectShareInGroupDiv')
                    .removeClass('d-none')
                    .find('#selectShareInGroup')
                    .attr('required', true)
                    .end();
                break;
            case 'message':
                $('.selectShareInMessageDiv')
                    .removeClass('d-none')
                    .find('#selectShareInMessage')
                    .attr('required', true)
                    .end();

                break;
        }
    });

    $('#formShare').off('submit').on('submit', function (event) {
        let form = $(this);
        event.preventDefault();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#shareModal').modal('hide');
            },
            success: function () {
                showMessage({
                    color: "body",
                    message: 'Вы успешно поделились записью'
                });

                $.pjax.reload({ container: "#pjax-container", async: false });
            }
        });

        resetForm()
    });

    $('.getCommentsButton').off('click').on('click', function () {
        let button = $(this);
        let page = button.attr('data-page');
        let id = button.attr('data-id');
        let type = button.attr('data-type');

        $.ajax({
            url: '/getComment',
            type: 'post',
            data: { page, id, type },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                button.attr('disabled', true)
                button.html(`
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Загрузка...</span>
                `)
            },
            success: function (data) {
                data = Object.values(data)
                commentBlock = button.parents('.commentsBlock');

                if (data.length > 0) {
                    data.forEach(item => {
                        let comment = $($('#comment-template').html())
                            .find('.avatar')
                            .attr('src', item.author.avatar.thumbnailPath)
                            .end()
                            .find('.profileNameLink')
                            .attr('href', `id${item.author.id}`)
                            .text(`${item.author.firstname} ${item.author.surname}`)
                            .end()
                            .find('.content')
                            .text(item.content)
                            .end()
                            .find('.sent-at')
                            .attr('data-bs-title', item.createdAtIsoFormat)
                            .text(item.createdAtDiffForHumans)
                            .end();

                        if (item.permission) {
                            comment
                                .find('.deleteComment input[name="id"]')
                                .val(item.id)
                                .end()
                        } else {
                            comment
                                .find('.deleteComment')
                                .remove()
                                .end()
                        }

                        commentBlock.append(comment);
                    });
                }

                button.parents('.d-flex').first().insertAfter(commentBlock.children().last())
                button.attr('data-page', ++page);
                button.attr('disabled', false)
                button.text('Загрузить ещё...');
            }
        });
    });
}

function resetForm() {
    $('#formShare').trigger("reset");
    $('.selectShareInGroupDiv').addClass('d-none');
    $('.selectShareInMessageDiv').removeClass('d-none')

    $('#selectShareInGroup').val('').trigger('change');
    $('#selectShareInMessage').val('').trigger('change');
}
