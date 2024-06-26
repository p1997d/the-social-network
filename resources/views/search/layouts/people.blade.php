<ul class="list-group list-group-flush">
    @forelse ($items as $user)
        <li class="list-group-item border-0 d-flex gap-2 px-0">
            <div class="position-relative">
                <a href="{{ route('profile', $user->id) }}">

                    @include('layouts.avatar', [
                        'model' => $user,
                        'width' => '64px',
                        'height' => '64px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false,
                    ])

                    @if ($user->online()['status'])
                        @if (!$user->online()['mobile'])
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
                    <a href="{{ route('profile', $user->id) }}" class="link-body-emphasis">
                        {{ $user->firstname }} {{ $user->surname }}
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('messages', ['to' => $user->id]) }}" class="fs-7">
                        Написать сообщение
                    </a>
                    @if ($user->id !== auth()->user()->id)
                        @include('layouts.forms.friends', [
                            'friendForm' => $user->friendForm(),
                            'buttons' => false
                        ])
                    @endif
                </div>
            </div>
        </li>
    @empty
        <div class="text-center text-secondary">Ваш запрос не дал результатов</div>
    @endforelse
</ul>
