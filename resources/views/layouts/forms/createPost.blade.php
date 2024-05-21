<form method="POST" enctype="multipart/form-data" action="{{ route('posts.create') }}" id="sendPostForm">
    @csrf
    <div class="d-flex gap-1">
        <input type="hidden" name="{{ $recipientName }}" value="{{ $recipientValue }}">

        <label for="uploadFile" class="btn btn-outline-secondary"><i class="bi bi-paperclip"></i></label>
        <input type="file" id="uploadFile" class="d-none uploadFile" name="files[]" multiple></input>

        <input type="text" enterkeyhint="send" class="form-control" style="resize:none" id="content"
            name="content" autocomplete="off"
            placeholder="{{ $contentPlaceholder }}">
        <div id="forButton">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-send"></i></button>
        </div>
    </div>

    <div class="listfiles d-flex gap-2 mx-5 flex-wrap"></div>
</form>
