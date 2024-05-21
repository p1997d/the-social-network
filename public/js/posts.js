$(document).ready(async function () {
    initializationPosts();
});

$(document).on('pjax:end', function () {
    initializationPosts();
});

function initializationPosts() {
    $('#sendPostForm').off('submit').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        let fileInput = $('#uploadFile')[0];

        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('attachments[]', fileInput.files[i]);
        }

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                closeUpdate();
            },
            success: function (data) {
                $('.listfiles').find('.fileBadge').remove();

                $('.emptyMessage').remove();

                post = $($('#post-template').html());
                attachments = getAttachments(data.postAttachments)

                post.find('.postImageLink')
                    .attr('href', data.postHeaderLink)
                    .end()
                    .find('.postAvatar')
                    .attr('src', data.postHeaderAvatarModel.thumbnailPath)
                    .end()
                    .find('.postLink')
                    .attr('href', data.postHeaderLink)
                    .text(data.postHeaderTitle)
                    .end()
                    .find('.postContent')
                    .text(data.postDecryptContent)
                    .append(attachments)
                    .end()
                    .find('.postDate')
                    .text(data.postDate)
                    .end();

                if (data.postAdminCondition) {
                    post.find('.postDelete').attr('action', `/posts/${data.post.id}/delete`);

                } else {
                    post.find('.postDropdown').remove();
                }

                post.find('.setLike')
                    .attr('data-like', data.postSetLike.data)
                    .find('input[name="id"]')
                    .val(data.postSetLike.id)
                    .end()
                    .find('input[name="type"]')
                    .val(data.postSetLike.type)
                    .end()
                    .find('button[type="submit"]')
                    .prop('disabled', false)
                    .attr('class', data.postSetLike.class)
                    .find('.countLikes')
                    .text(data.postSetLike.count)
                    .end()
                    .end();

                post.prependTo('.postsList');

                initializationInteraction();
                initializationPosts();
            },
            error: function () {
                $('.listfiles').find('.fileBadge').remove();
            }
        });

        $(this)[0].reset();
    });

    $('.postDelete').off('submit').on('submit', function (event) {
        event.preventDefault();
        form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                form.closest('.card').remove();

                if ($('.postsList').children().length === 0) {
                    $('.postsList').append(
                        $('<div>').attr('class', 'card shadow m-0 emptyMessage').append(
                            $('<div>').attr('class', 'card-body').append(
                                $('<p>').attr('class', 'text-center').text('На стене пока нет ни одной записи')
                            )
                        )
                    );
                }
            }
        });
    });
}
