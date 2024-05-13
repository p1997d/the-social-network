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
                $.pjax.reload({ container: "#pjax-container", async: false });
                $('.listfiles').find('.fileBadge').remove();
            },
            error: function () {
                $('.listfiles').find('.fileBadge').remove();
            }
        });

        $(this)[0].reset();
    });
}
