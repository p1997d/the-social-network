<div class="attachments">
    @if ($message->attachments())
        <div
            class="row row-cols-{{ $message->attachments('image')->count() < 5 ? $message->attachments('image')->count() : 5 }} g-2 my-1">
            @foreach ($message->attachments() as $attachment)
                @switch(explode('/', $attachment->type)[0])
                    @case('image')
                        @if (!$attachment->deleted_at)
                            <div class="col">
                                <div class="openImageModal" data-user="{{ $attachment->author }}"
                                    data-photo="{{ $attachment->id }}" data-type="messages" tabindex="0">
                                    <img src="{{ asset("storage/files/$attachment->path") }}" class="photos rounded" />
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <span class="text-secondary">Фотография удалена</span>
                            </div>
                        @endif
                    @break

                    @case('audio')
                        <div class="col-12">
                            <audio class="player" controls>
                                <source src="{{ asset("storage/files/$attachment->path") }}" type="{{ $attachment->type }}" />
                            </audio>
                        </div>
                    @break

                    @case('video')
                        <div class="col-12">
                            <video class="player" controls>
                                <source src="{{ asset("storage/files/$attachment->path") }}" type="{{ $attachment->type }}" />
                            </video>
                        </div>
                    @break

                    @default
                        <div class="col">
                            <a href="{{ asset("storage/files/$attachment->path") }}" target="_blank"
                                class="link-underline link-underline-opacity-0">
                                <div class="card">
                                    <div class="card-body d-flex justify content-start gap-2">
                                        <i class="bi bi-file-earmark"></i>
                                        <div>{{ $attachment->name }}</div>
                                        <div class="text-secondary">{{ $attachment->size() }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @break
                @endswitch
            @endforeach
        </div>
    @endif
</div>
