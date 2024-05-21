<div class="d-flex justify-content-start flex-wrap gap-2" id="listPhotos">
    @forelse ($photos as $i => $photo)
        @if ($loop->first || (isset($photos[$i - 1]) && !$photos[$i - 1]->createdTheSameDay($photo)))
            <div class="w-100 pt-3 pb-1 text-secondary">
                <p class="m-0 p-0">{{ $photo->date() }}</p>
            </div>
        @endif

        <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
            data-group="{{ $type }}" tabindex="0">
            <img src="{{ $photo->thumbnailPath }}" class="photos rounded" />
        </div>
    @empty
        <div class="w-100 text-center emptyMessage">
            <p>{{ $emptyMessage }}</p>
        </div>
    @endforelse
</div>
