<ul class="list-group">
    @forelse ($friends as $friend)
        <li class="list-group-item border-0 d-flex gap-2 px-0">
            <div class="position-relative">
                <a href="{{ route('profile', $friend->id) }}">

                    @include('layouts.avatar', [
                        'model' => $friend,
                        'width' => '64px',
                        'height' => '64px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false,
                    ])

                    @if ($friend->online()['status'])
                        @if (!$friend->online()['mobile'])
                            <span
                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                            </span>
                        @else
                            <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 text-success">
                                <i class="bi bi-phone"></i>
                            </span>
                        @endif
                    @endif
                </a>
            </div>
            <div class="w-100 border-bottom">
                <div class="fw-bold">
                    <a href="{{ route('profile', $friend->id) }}" class="link-body-emphasis">
                        {{ $friend->firstname }} {{ $friend->surname }}
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('messages', ['to' => $friend->id]) }}" class="fs-7">
                        Написать сообщение
                    </a>
                    @include('layouts.forms.friends', [
                        'friendForm' => $friend->friendForm(),
                        'buttons' => false,
                    ])
                </div>
            </div>
        </li>
    @empty
        <div class="text-center">
            <p>Ни одного друга не найдено</p>
        </div>
    @endforelse
</ul>
