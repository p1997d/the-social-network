<div class="container attachments">
    @if ($message->getAttachments())
        <div
            class="row row-cols-{{ $message->getAttachments('image')->count() < 5 ? $message->getAttachments('image')->count() : 5 }} g-2 my-2">
            @foreach ($message->getAttachments() as $attachment)
                @switch(explode('/', $attachment->type)[0])
                    @case('image')
                        <div class="col">
                            <div data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-image="{{ asset("storage/files/$attachment->path") }}">
                                <img src="{{ asset("storage/files/$attachment->path") }}" class="message_image"/>
                            </div>
                        </div>
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
                                        <div class="text-secondary">{{ $attachment->getSize() }}</div>
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
