$(document).ready(function () {
    initializeImageInteractions();
    showModal();
    showUploadPhotModal();
    $(document).bind('keydown', function (e) {
        if (e.keyCode == 39) {
            $('button.carousel-control-next').trigger('click');
        }

        else if (e.keyCode == 37) {
            $('button.carousel-control-prev').trigger('click');
        }
    });
});

$(document).on('pjax:end', function () {
    initializeImageInteractions();
    showModal();
    showUploadPhotModal();
});

function showUploadPhotModal() {
    const url = new URL(window.location);
    const modal = url.searchParams.get('modal');
    if (modal) {
        $('#uploadphoto').modal('show');
    }
}

function initializeImageInteractions() {
    $('.openImageModal')
        .off('click keypress')
        .on('click keypress', function () {
            const [file, user, group] = getDataFromFile(this, 'photo');
            const photo = setUrl('photo', file, user, group);

            showModal();
        });

    $('#imageModal')
        .off('hide.bs.modal')
        .on('hide.bs.modal', () => {
            clearUrl();
        });

    $('#carouselIndicators')
        .off('slid.bs.carousel')
        .on('slid.bs.carousel', (event) => {
            const [file, user, group] = getDataFromFile(event.relatedTarget, 'photo');
            const photo = setUrl('photo', file, user, group);

            setCounter();
            checkDelete();
        });

    $('.photoDeleteButton')
        .off('click')
        .on('click', () => {
            let photo = getPhotoIdFromUrl();

            $.ajax({
                url: '/photos/delete',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    photo,
                },
                success: function (data) {
                    if (data.button === "restore") {
                        $('#carouselIndicators').find('div.carousel-item.active').addClass('deleted');
                    } else {
                        $('#carouselIndicators').find('div.carousel-item.active').removeClass('deleted');
                    }
                    checkDelete();
                }
            })
        });

    $('#formPhotoUpload')
        .off('submit')
        .on('submit', function (event) {
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
                    $('#uploadphoto').modal('hide');
                    showLoadingToast(number);
                },
                error: function (data) {
                    showMessage({
                        color: "danger",
                        message: data.responseJSON.message
                    });
                },
                success: function (data) {
                    $.pjax.reload({ container: "#pjax-container", async: false });
                    $(`.loadingToast[data-number="${number}"]`).remove();
                    showMessage(data.notification);
                }
            });

            $(this)[0].reset();
        });
}

function getPhotoIdFromUrl() {
    const url = new URL(window.location);
    const content = url.searchParams.get('content');
    const photo = content.split('_')[2];

    return photo;
}

function getPhoto(id) {
    const urlParams = new URLSearchParams(window.location.search);
    const to = urlParams.get('to');
    const chat = urlParams.get('chat');
    const content = urlParams.get('content');

    $.ajax({
        url: '/photos/getPhoto',
        type: 'GET',
        data: {
            id, to, chat, content
        },
        success: function (data) {
            if (!data.photo.deleted_at) {
                $('#imageModal').modal('show');
            } else {
                clearUrl();
                showAccessError()
                return;
            }

            setCarousel(data);

            $('.photoComments').removeClass('placeholder-glow');
            $('.photoComments').find('.photoModalAvatar').attr('src', data.photoModalAvatar).removeClass('placeholder');
            $('.photoComments').find('.photoModalLink').attr('href', data.photoModalLink.href).text(data.photoModalLink.title);
            $('.photoComments').find('.photoModalImageLink').attr('href', data.photoModalLink.href);
            $('.photoComments').find('.photoModalDate').text(data.photoModalDate);
            $('.photoComments').find('.setLike')
                .attr('data-like', data.photoModalSetLike.data)
                .find('input[name="id"]')
                .val(data.photoModalSetLike.id)
                .end()
                .find('input[name="type"]')
                .val(data.photoModalSetLike.type)
                .end()
                .find('button[type="submit"]')
                .prop('disabled', false)
                .attr('class', data.photoModalSetLike.class)
                .find('.countLikes')
                .text(data.photoModalSetLike.count)
                .end()
                .end();

            $('.photoComments').find('.photoModalComments').text(data.photoModalComments);

            initializationInteraction();
            setCounter();
            checkDelete();

            if (userId !== data.photo.author) {
                $('.photoDeleteButton').remove();
            }
        }
    });
}

function setCarousel(data) {
    let list = $('.imageModalCarousel');
    list.empty();

    data.content.forEach(item => {
        addPhoto(item, data).appendTo(list);
    });

    initializeImageInteractions();
}

function addPhoto(item, data) {
    let photo = $($('#photo-template').html());

    photo.attr('data-photo', item.id)
        .attr('data-user', item.author)
        .attr('data-group', data.groupContent)
        .find('.displayedImage')
        .attr('src', item.path)
        .end();

    if (item.id == data.activeContent) {
        photo.addClass('active')
    }

    return photo;
}

function setCounter() {
    let photosLength = $('#carouselIndicators .carousel-item').length;
    let currentPhoto = $('#carouselIndicators').find('div.carousel-item.active').index() + 1;

    $('.photoCounter')
        .empty()
        .append(`${currentPhoto} из ${photosLength}`)
        .addClass('text-secondary');
}

function checkDelete() {
    if ($('#carouselIndicators').find('div.carousel-item.active').hasClass('deleted')) {
        $('.photoDeleteButton').text('Восстановить');
        let div = $('<div>')
            .addClass('shadow py-3 px-4 z-3 d-flex justify-content-between photoRestore')
            .append('<div>Фотография удалена.</div>')

        $('#carouselIndicators').prepend(div)
    } else {
        $('.photoDeleteButton').text('Удалить');
        $('.photoRestore').remove();
    }
}
