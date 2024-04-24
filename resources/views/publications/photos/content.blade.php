@php
    use Carbon\Carbon;
@endphp
<div class="d-flex justify-content-start flex-wrap gap-2">
    @forelse ($photos as $i => $photo)
        @if (
            $loop->first ||
                (isset($photos[$i + 1]) &&
                    !Carbon::parse($photos[$i + 1]->created_at)->isSameDay(Carbon::parse($photo->created_at))))
            <div class="w-100 pt-3 pb-1 text-secondary">
                <p class="m-0 p-0">{{ $photo->date() }}</p>
            </div>
        @endif

        <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
            data-group="{{ $type }}" tabindex="0">
            <img src="{{ $photo->thumbnailPath }}" class="photos rounded" />
        </div>
    @empty
        <div class="w-100 text-center">
            @if (auth()->user()->id == $user->id)
                <p>Вы ещё не загружали фото</p>
            @else
                <p>{{ $user->firstname }} ещё не
                    добавил{{ $user->sex == 'female' ? 'а' : '' }}
                    фотографии
                </p>
            @endif
        </div>
    @endforelse
</div>
