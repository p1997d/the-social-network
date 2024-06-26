<div class="card shadow">
    <div class="card-header">
        <a href="{{ route('photos', ['id' => $user->id]) }}" class="link-body-emphasis">Фотографии</a>
        <span class="text-secondary">{{ $user->photos()->count() }}</span>
    </div>
    <div class="card-body container text-center">
        <div class="row">
            @forelse ($user->photos()->take(4) as $photo)
                <div class="col p-2">
                    <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
                        tabindex="0">
                        <img src="{{ $photo->thumbnailPath }}" class="photos rounded object-fit-cover"
                            style="aspect-ratio: 1 / 1; max-height: 160px" />
                    </div>
                </div>
            @empty
                <div class="col-12 p-2">
                    @if (auth()->user()->id == $user->id)
                        <p>Вы ещё не добавили фото</p>
                    @else
                        <p>{{ $user->firstname }} ещё
                            не добавил{{ $user->sex == 'female' ? 'а' : '' }}
                            фотографии
                        </p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
