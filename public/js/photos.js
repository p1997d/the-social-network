$(document).ready(function () {
    initializeImageInteractions();

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
});

function initializeImageInteractions() {
    $('.openImageModal')
        .off('click keypress')
        .on('click keypress', function () {

            const [file, user, group] = getDataFromFile(this, 'photo');
            setUrl('photo', file, user, group);

            $('#imageModal').modal('show');
            $.pjax.reload({ container: "#imageModal", async: false });
        });

    $('#imageModal')
        .off('hide.bs.modal')
        .on('hide.bs.modal', () => {
            clearUrl();
        });

    $('#imageModal')
        .off('show.bs.modal')
        .on('show.bs.modal', () => {
            let photo = getPhotoIdFromUrl();

            getPhotoInfo(photo);
        });

    $('#carouselIndicators')
        .off('slide.bs.carousel')
        .on('slide.bs.carousel', (event) => {

            const [file, user, group] = getDataFromFile(event.relatedTarget, 'photo');
            const photo = setUrl('photo', file, user, group);

            getPhotoInfo(photo, event.to + 1);
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
                    $('.photoDeleteButton').html(data.button);

                    if (data.button == "Восстановить") {
                        let div = $('<div>')
                            .addClass('shadow py-3 px-4 z-3 d-flex justify-content-between photoRestore')
                            .append('<div>Фотография удалена.</div>')

                        $('#carouselIndicators').prepend(div)
                    } else {
                        $('.photoRestore').remove();
                    }
                }
            })
        });

    $('#formPhotoUpload')
        .off('submit')
        .on('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#uploadphoto').modal('hide');
                },
                error: function(data) {
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

function getPhotoIdFromUrl() {
    const url = new URL(window.location);
    const content = url.searchParams.get('content');
    const photo = content.split('_')[2];

    return photo;
}

function getPhotoInfo(photo, currentPhoto = null) {
    $.ajax({
        url: '/photos/getPhoto',
        type: 'GET',
        data: {
            id: photo,
        },
        beforeSend: function () {
            $('.photoComments').html(`<div class="w-100 h-100 d-flex justify-content-center align-items-center">
                <div class="spinner-border" role="status"></div>
            </div>`)

            $('.photoCounter').html(`<div class="spinner-border spinner-border-sm" role="status"></div>`)
        },
        success: function (data) {
            let header = $('<div>')
                .addClass('card-header d-flex align-items-center gap-1');

            let avatar = $('<div>').append(
                $('<img>')
                    .addClass('rounded-circle object-fit-cover')
                    .attr('width', '40px')
                    .attr('height', '40px')
                    .attr('src', data.avatar)
            ).appendTo(header);

            let div = $('<div>').appendTo(header);

            let name = $('<div>').append(
                $('<a>')
                    .addClass('link-body-emphasis')
                    .attr('href', `/id${data.author.id}`)
                    .html(`${data.author.firstname} ${data.author.surname}`)
            ).appendTo(div);

            let date = new Date(data.photo.created_at).toLocaleString("ru", {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });

            let time = $('<div>').addClass('text-secondary fs-7').html(date).appendTo(div);

            let body = $('<div>')
                .addClass('card-body')
                .addClass('text-center text-secondary')
                .addClass('w-100 h-100 d-flex justify-content-center align-items-center')
                .html('Возможность комментирования этой фотографии ограничена.');

            $('.photoComments')
                .html('')
                .append(header)
                .append(body);

            let groupContent = {
                profile: {
                    description: 'Фото профиля',
                    url: `photos?id=${data.author.id}&type=profile`
                },
                uploaded: {
                    description: 'Загруженные фото',
                    url: `photos?id=${data.author.id}&type=uploaded`
                },
                messages: {
                    description: '',
                    url: ''
                }
            }

            let photosLength = $('#carouselIndicators .carousel-item').length;

            if (!currentPhoto) {
                currentPhoto = $('#carouselIndicators').find('div.carousel-item.active').index() + 1;
            }

            if (photosLength <= 1) {
                $('#carouselIndicators .carousel-control-prev, #carouselIndicators .carousel-control-next').hide();
            }

            let typeLink = $('<a>')
                .attr('href', groupContent[data.photo.group].url)
                .html(groupContent[data.photo.group].description)
                .addClass('link-body-emphasis');

            $('.photoCounter')
                .html('')
                .append(typeLink)
                .append(`${currentPhoto} из ${photosLength}`)
                .addClass('text-secondary');

            if (userId !== data.photo.author) {
                $('.photoDeleteButton').remove();
            }
        }
    });
}

function getPhoto(file){
    $.ajax({
        url: '/photos/getPhoto',
        type: 'GET',
        data: {
            id: file,
        },
        success: function (data) {
            if (!data.photo.deleted_at && $(`#carouselIndicators .carousel-item[data-photo='${data.photo.id}']`).length) {
                $('#imageModal').modal('show');
            } else {
                clearUrl();
                showAccessError()
            }
        }

    });
}
