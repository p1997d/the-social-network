let url, recipient, typeRecipient;

var page;

$(document).ready(function () {
    getURL();
    restartingHandlers();
    checkRead();

    $('<input>').attr({
        type: 'hidden',
        name: typeRecipient,
        value: recipient
    }).prependTo('.searchForm .input-group');

    resetPage();
});

$(document).on('pjax:end', function () {
    getURL();
    resetPage();
    restartingHandlers();
});

$(window).scroll(function () {
    checkRead();

    if ($(window).scrollTop() == 0) {
        $.ajax({
            type: 'GET',
            url: url.href,
            success: function (response) {
                let height1 = $('.messages-list-group').height()
                $html = $($.parseHTML(response)).find(".messages-list-group");

                if ($html.length > 0) {
                    $('.messages-list-group').append($html.children('.list-group-item'));
                    let height2 = $('.messages-list-group').height()
                    page++;
                    url.searchParams.set('page', page);
                    $(document).scrollTop(height2 - height1);
                }
                restartingHandlers()
            }
        });
    }
});

function getURL() {
    url = new URL(window.location.href);
    recipient = url.searchParams.get('to') || url.searchParams.get('chat');
    typeRecipient = url.searchParams.has('to') ? 'to' : url.searchParams.has('chat') ? 'chat' : null;
}

function restartingHandlers() {
    $('[data-bs-toggle="tooltip"]').tooltip();

    $('#deleteModal').off('show.bs.modal');
    $('.editButton, #closeUpdate, .search-button, .close-search-button').off('click');
    $('.messageForm').off('submit');
    $('#uploadFile').off('change');

    if ($('#deleteModal')) {
        $('#deleteModal').on('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const messageId = button.getAttribute('data-bs-messageid');
            const myMessage = Boolean(button.getAttribute('data-bs-mymessage'));

            if (typeRecipient == 'to') {
                $('#formDelete').attr('action', `/messages/delete/${messageId}`);
            } else if (typeRecipient == 'chat') {
                $('#formDelete').attr('action', `/messages/chat/delete/${messageId}`);
            }

            $('#checkForAll').html(myMessage ? `<div class="form-check">
                <input class="form-check-input" type="checkbox" id="deleteForAll" name="deleteForAll">
                <label class="form-check-label" for="deleteForAll">Удалить для всех</label>
            </div>` : '');
        });
    }

    $('.editButton').on('click', function () {
        let messageId = this.getAttribute('data-bs-messageid');

        $.ajax({
            url: '/messages/getMessage',
            type: "GET",
            data: {
                id: messageId,
                typeRecipient
            },
            success: function (data) {
                if (typeRecipient == 'to') {
                    $('#formMessage #sendMessageForm').attr('action', `/messages/update/${messageId}`);
                } else if (typeRecipient == 'chat') {
                    $('#formMessage #sendMessageForm').attr('action', `/messages/chat/update/${messageId}`);
                }

                $('#formMessage p').show();
                $('#formMessage #sendMessageForm input[type="text"]').val(data.content).addClass("updateTextarea");

                $('#formMessage #forButton')
                    .html('<button type="submit" class="btn btn-text"><i class="bi bi-check2-circle"></i></button>')
                    .attr('data-bs-messageid', messageId);

                checkTextareaChange();
            },
        });
    });

    $('#closeUpdate').on('click', function () {
        closeUpdate();
    });

    $('.messageForm').on('submit', function (event) {
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
                changeMessagesBlock(data);
                $.pjax.reload({ container: ".sidebar", async: false });
                $(document).scrollTop($(document).height());
                $('.listfiles').find('.fileBadge').remove();
                restartingHandlers();
            },
            error: function () {
                $('.listfiles').find('.fileBadge').remove();
            }
        });

        $(this)[0].reset();
    });

    $('.search-button').on('click', function () {
        $('.main-message-header').addClass('d-none').removeClass('d-flex');
        $('.search-message-header').addClass('d-flex').removeClass('d-none');
    });

    $('.close-search-button').on('click', function () {
        $('.main-message-header').addClass('d-flex').removeClass('d-none');
        $('.search-message-header').addClass('d-none').removeClass('d-flex');
    });

    $('#uploadFile').on('change', function () {
        const fileInput = $('#uploadFile')[0];
        const maxSize = 25 * 1024 * 1024;

        if (fileInput.files.length > 10) {
            $('#errorModal .modal-body p').text('Вы можете прикрепить к сообщению не более 10 файлов.');
            $('#errorModal').modal('show');
            return;
        }

        for (var i = 0; i < fileInput.files.length; i++) {
            if (fileInput.files[i].size > maxSize) {
                $('#errorModal .modal-body p').text('Размер файла не должен превышать 25 МБ.');
                $('#errorModal').modal('show');
                return;
            }
        }

        viewFileList(this);
    });
}

function closeUpdate() {
    $('.listfiles').find('.fileBadge').remove();
    $('.updateTextarea, #uploadFile').off("input");

    if (typeRecipient == 'to') {
        $('#formMessage #sendMessageForm').attr('action', `/messages/send/${recipient}`);
    } else if (typeRecipient == 'chat') {
        $('#formMessage #sendMessageForm').attr('action', `/messages/chat/send/${recipient}`);
    }

    $('#formMessage p').hide();
    $('#formMessage input[type="text"]').val('').removeClass("updateTextarea");
    $('#formMessage #forButton')
        .html('<button type="submit" class="btn btn-text"><i class="bi bi-send"></i></button>')
        .attr('data-bs-messageid', null);
}

function checkTextareaChange() {
    $('.updateTextarea, #uploadFile').on("input", function () {
        check();
    });

    function check() {
        if (!$(".updateTextarea").val() && $('#uploadFile')[0].files.length === 0) {
            let messageId = $('#formMessage #forButton').attr('data-bs-messageid')
            $('#formMessage #forButton').html(`<button class="btn btn-text" data-bs-toggle="modal"
            data-bs-target="#deleteModal" data-bs-messageid="${messageId}"
            data-bs-mymessage="true">
            <i class="bi bi-trash"></i>
        </button>`);
        } else {
            $('#formMessage #forButton').html('<button type="submit" class="btn btn-text"><i class="bi bi-check2-circle"></i></button>');
        }
    }
}

function checkRead() {
    var unread = $('.unread:in-viewport');
    if (unread.length > 0) {
        $.ajax({
            url: "/messages/checkRead",
            type: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: unread.attr('id')
            },
            success: function (messageIds) {
                messageIds.forEach(item => {
                    $(`.unread#${item}`).removeClass('unread')
                });
                $.pjax.reload({ container: ".sidebar", async: false });
                $.pjax.reload({ container: ".header-pjax", async: false });
            }
        });
    }
}

function isVisible(tag) {
    let t = $(tag);
    let w = $(window);
    let wt = w.scrollTop();
    let tt = t.offset().top;
    let tb = tt + t.height();
    return ((tb <= wt + w.height()) && (tt >= wt));
}

function resetPage() {
    url = new URL(window.location.href);

    if (window.location.pathname === '/messages' && (url.searchParams.has('to') || url.searchParams.has('chat'))) {
        page = 2;
        url.searchParams.set('page', page);
        if ($('.unread').length) {
            $(document).scrollTop($('.unread:last').offset().top - $('.messages-list-group').offset().top);
        } else {
            $(document).scrollTop($(document).height());
        }
    }
}

function changeMessagesBlock(data) {
    switch (data.type) {
        case 'create':
            $row = getAttachments(data);

            $(".list-group-item-empty").clone()
                .removeClass('list-group-item-empty')
                .addClass('list-group-item-message')
                .addClass(userId === data.sender.id ? '' : 'unread')
                .attr('id', data.message.id)
                .find('.profileImageLink').attr('href', `/id${data.sender.id}`)
                .children('img').attr('src', data.senderAvatar.thumbnailPath).end()
                .end()
                .find('.profileNameLink').text(data.sender.firstname).attr('href', `/id${data.sender.id}`).end()
                .find('.sent-at').text(data.sentAtFormat).end()
                .find('.editButton').attr("data-bs-messageid", data.message.id).end()
                .find('.deleteModal').attr("data-bs-messageid", data.message.id).attr("data-bs-mymessage", userId === data.sender.id).end()
                .find('.content')
                .prepend(data.decryptContent)
                .find('.attachments')
                .append($row)
                .end()
                .end()
                .prependTo(".messages-list-group");
            break;
        case 'update':
            $row = getAttachments(data);

            let $span = $('<span>')
                .addClass('text-secondary')
                .attr('data-bs-toggle', 'tooltip')
                .attr('data-bs-placement', 'top')
                .attr('data-bs-custom-class', 'custom-tooltip')
                .attr('data-bs-title', `изменено ${data.changedAtFormat}`)
                .text(' (ред.)');

            $(`.list-group-item-message#${data.message.id}`)
                .find('.content')
                .text(data.decryptContent)
                .append($span)
                .end()
                .find('.attachments')
                .html($row)
                .end();
            break;
        case 'delete':
            $(`.list-group-item-message#${data.message.id}`).remove();
            $('.modal').modal('hide');
            break;
    }
}

function viewFileList(fileInput) {
    $('.listfiles').find('.fileBadge').remove();

    for (let i = 0; i < fileInput.files.length; i++) {
        let file = fileInput.files[i];

        $('.fileBadgeEmpty')
            .clone()
            .removeClass('d-none fileBadgeEmpty')
            .addClass('d-flex fileBadge')
            .find('.file-name')
            .text(file.name)
            .end()
            .find('.btn-close')
            .attr('onclick', `removeFile(${i})`)
            .end()
            .appendTo('.listfiles');
    };
}

function getAttachments(data) {
    let $row = $('<div>').addClass(`row row-cols-5 g-2 my-2`);

    data.attachments.forEach(item => {
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
                $col = $('<div>').addClass('col').appendTo($row);
                $a = $('<a>')
                    .attr('href',item.path)
                    .attr('target', '_blank')
                    .addClass('link-underline link-underline-opacity-0')
                    .appendTo($col);
                $card = $('<div>').addClass('card').appendTo($a);
                $cardBody = $('<div>')
                    .addClass('card-body d-flex justify content-start gap-2')
                    .append('<i class="bi bi-file-earmark"></i>')
                    // .append(`<div> ${item.name} </div>`)
                    .append(`<div> Файл </div>`)
                    .append(`<div class="text-secondary"> ${getSize(item.size)} </div>`)
                    .appendTo($card);
                break;
        }
    });

    return $row;
}

function removeFile(removeIndex) {
    const dt = new DataTransfer();

    let fileInput = $('#uploadFile')[0];

    for (let i = 0; i < fileInput.files.length; i++) {
        let file = fileInput.files[i];

        if (i !== removeIndex) {
            dt.items.add(file);
        }
    };

    fileInput.files = dt.files

    $(fileInput).trigger('input');

    viewFileList(fileInput);
}

function getSize(bite) {
    base = Math.log(bite) / Math.log(1024);
    suffixes = ['', 'КБ', 'МБ', 'ГБ', 'ТБ'];
    size = Math.round(Math.pow(1024, base - Math.floor(base)) * 10) / 10 + ' ' + suffixes[Math.floor(base)]
    return size
}
