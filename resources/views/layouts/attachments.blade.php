<div class="attachments">
    <div class="row row-cols-5 g-2 my-1">
        @foreach ($model->attachmentsPhotos as $photo)
            @if (!$photo->deleted_at)
                <div class="col">
                    <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
                        data-group="{{ $group }}" tabindex="0">
                        <img src="{{ $photo->thumbnailPath }}" class="photos rounded" />
                    </div>
                </div>
            @else
                <div class="col-12">
                    <span class="text-secondary">Фотография удалена</span>
                </div>
            @endif
        @endforeach
        @foreach ($model->attachmentsAudios as $audio)
            <div class="col-12">
                <audio class="player" controls>
                    <source src="{{ $audio->path }}" type="{{ $audio->type }}" />
                </audio>
            </div>
        @endforeach
        @foreach ($model->attachmentsVideos as $video)
            <div class="col-12">
                <video class="player" controls>
                    <source src="{{ $video->path }}" type="{{ $video->type }}" />
                </video>
            </div>
        @endforeach
        @foreach ($model->attachmentsFiles as $file)
            <div class="col-auto">
                <a href="{{ route('files.download', $file->id) }}" target="_blank"
                    class="link-underline link-underline-opacity-0">
                    <div class="card">
                        <div class="card-body d-flex justify-content-start gap-2">
                            <i class="bi bi-file-earmark"></i>
                            <div>{{ $file->name }}</div>
                            <div class="text-secondary">{{ $file->size() }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
