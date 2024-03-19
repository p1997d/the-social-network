@forelse ($videos as $i => $video)
    <div></div>
@empty
    <div class="w-100 text-center">
        @if (auth()->user()->id == $user->id)
            <p>Вы ещё не загружали видеозаписи</p>
        @else
            <p>{{ $user->firstname }} ещё не
                добавил{{ $user->sex == 'female' ? 'а' : '' }}
                видеозаписи
            </p>
        @endif
    </div>
@endforelse
