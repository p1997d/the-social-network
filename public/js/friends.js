$(document).ready(function () {
    initializeFriends();
});

$(document).on('pjax:end', function () {
    initializeFriends();
});

function initializeFriends() {
    $('.formFriends').off('submit');

    $('.formFriends').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function (data) {
                console.log(data)
                let listButtons = $('.friendFormsButtons');
                let listLinks = $('.friendFormsLinks');

                listButtons.empty();
                listLinks.empty();

                data.form.forEach(item => {
                    $($('#friend-template').html())
                        .attr('action', item.link)
                        .attr('class', 'w-100 formFriends')
                        .find('.button')
                        .attr('class', `btn ${item.color} w-100`)
                        .end()
                        .find('.icon')
                        .attr('class', `bi ${item.icon}`)
                        .end()
                        .find('.titleFriend')
                        .removeClass('titleFriend')
                        .text(item.title)
                        .end()
                        .appendTo(listButtons);

                    $('<span>').attr('class', 'separator').text('·').appendTo(listLinks);

                    $($('#friend-template').html())
                        .attr('action', item.link)
                        .attr('class', 'formFriends')
                        .find('.button')
                        .attr('class', `btn btn-link p-0 fs-7`)
                        .end()
                        .find('.icon')
                        .remove()
                        .end()
                        .find('.titleFriend')
                        .removeClass('titleFriend')
                        .text(item.title)
                        .end()
                        .appendTo(listLinks);
                });

                initializeFriends();
                updateCounters();
            }
        });
        $(this)[0].reset();
    })
}
