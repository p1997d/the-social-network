@auth
    @if ($user->id !== auth()->user()->id && $listCommonFriends->count() > 0)
        <div class="card shadow mb-3">
            <div class="card-header">
                <a href="{{ route('friends', ['id' => $user->id, 'section' => 'common']) }}"
                    class="link-body-emphasis">Общие друзья</a>
                <span class="text-secondary">{{ $listCommonFriends->count() }}</span>
            </div>
            <div class="card-body container text-center">
                <div class="row">
                    @foreach ($listCommonFriends->getRandomUsers(4) as $friend)
                        <div class="col p-2">
                            <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-link-emphasis"
                                href="{{ route('profile', $friend->id) }}">
                                <div class="w-100 position-relative">
                                    @include('layouts.avatar', [
                                        'model' => $friend,
                                        'width' => '48px',
                                        'height' => '48px',
                                        'class' => 'rounded-circle object-fit-cover',
                                        'modal' => false
                                    ])
                                    @if ($friend->online()['status'])
                                        @if (!$friend->online()['mobile'])
                                            <span
                                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                            </span>
                                        @else
                                            <span
                                                class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 fs-7 text-success">
                                                <i class="bi bi-phone"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="title text-center">{{ $friend->firstname }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        @if ($listOnline->count() > 0)
            <div class="card shadow mb-3">
                <div class="card-header">
                    <a href="{{ route('friends', ['id' => $user->id, 'section' => 'online']) }}"
                        class="link-body-emphasis">Друзья онлайн</a>
                    <span class="text-secondary">{{ $listOnline->count() }}</span>
                </div>
                <div class="card-body container text-center">
                    <div class="row">
                        @foreach ($listOnline->getRandomUsers(4) as $friend)
                            <div class="col p-2">
                                <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-link-emphasis"
                                    href="{{ route('profile', $friend->id) }}">
                                    <div class="w-100 position-relative">
                                        @include('layouts.avatar', [
                                            'model' => $friend,
                                            'width' => '48px',
                                            'height' => '48px',
                                            'class' => 'rounded-circle object-fit-cover',
                                            'modal' => false
                                        ])
                                        @if ($friend->online()['status'])
                                            @if (!$friend->online()['mobile'])
                                                <span
                                                    class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                                </span>
                                            @else
                                                <span
                                                    class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 fs-7 text-success">
                                                    <i class="bi bi-phone"></i>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="title text-center">{{ $friend->firstname }}</div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if ($listFriends->count() > 0 || auth()->user()->id == $user->id)
        <div class="card shadow mb-3">
            <div class="card-header">
                <a href="{{ route('friends', ['id' => $user->id]) }}" class="link-body-emphasis">Друзья</a>
                <span class="text-secondary">{{ $listFriends->count() }}</span>
            </div>
            <div class="card-body container text-center">
                <div class="row">
                    @forelse ($listFriends->getRandomUsers(4) as $friend)
                        <div class="col p-2">
                            <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-link-emphasis"
                                href="{{ route('profile', $friend->id) }}">
                                <div class="w-100 position-relative">
                                    @include('layouts.avatar', [
                                        'model' => $friend,
                                        'width' => '48px',
                                        'height' => '48px',
                                        'class' => 'rounded-circle object-fit-cover',
                                        'modal' => false
                                    ])
                                    @if ($friend->online()['status'])
                                        @if (!$friend->online()['mobile'])
                                            <span
                                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                            </span>
                                        @else
                                            <span
                                                class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 fs-7 text-success">
                                                <i class="bi bi-phone"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="title text-center">{{ $friend->firstname }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 p-2">
                            <p>У вас пока нет друзей</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endauth
