@if ($group->photos->count() > 0 || $group->admins()->contains('id', auth()->user()->id))
    <div class="card shadow mb-3">
        <div class="card-header">
            <a href="{{ route('photos', ['group' => $group->id]) }}" class="link-body-emphasis">Фотографии</a>
            <span class="text-secondary">{{ $group->photos->count() }}</span>
        </div>
        <div class="card-body">
            <div class="row row-cols-3 g-2">
                @forelse ($group->photos->take(6) as $photo)
                    <div class="col p-2">
                        <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
                            data-group="{{ 'group' . $group->id }}" tabindex="0">
                            <img src="{{ $photo->thumbnailPath }}" class="photos rounded object-fit-cover"
                                style="aspect-ratio: 1 / 1; max-height: 80px" />
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <a href="{{ route('photos', ['group' => $group->id, 'modal' => true]) }}"
                            class="btn btn-secondary btn-sm w-100">Добавить</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endif
