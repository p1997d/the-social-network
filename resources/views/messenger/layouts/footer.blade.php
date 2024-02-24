<div class="z-3 position-fixed messages-footer mt-5">
    <div class="bg-body-tertiary p-2 border rounded-bottom" id="formMessage">
        <form class="messageForm" id="sendMessageForm" method="POST" enctype="multipart/form-data"
            action="{{ route($type == 'chat' ? 'messages.chat.send' : 'messages.send', $recipient->id) }}">
            @csrf

            <p style="display: none" class="mb-2">
                Редактирование сообщения
                <button type="button" class="btn-close" aria-label="Close" style="width:0.5em; height:0.5em"
                    id="closeUpdate"></button>
            </p>
            <div class="d-flex gap-1">
                <label for="uploadFile" class="btn btn-text"><i class="bi bi-paperclip"></i></label>
                <input type="file" id="uploadFile" class="d-none" name="files[]" multiple></input>

                <input type="text" enterkeyhint="send" class="form-control" style="resize:none" id="content"
                    name="content" autocomplete="off" placeholder="Напишите сообщение...">
                <div id="forButton">
                    <button type="submit" class="btn btn-text"><i class="bi bi-send"></i></button>
                </div>
            </div>

            <div class="listfiles d-flex gap-2 mx-5 flex-wrap">
                <div class="badge fileBadgeEmpty text-bg-secondary mt-2 d-none justify-content-between align-items-center"
                    style="max-width: 50%">
                    <div class="file-name text-break text-truncate"></div>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
        </form>
    </div>
</div>
