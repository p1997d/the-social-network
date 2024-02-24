@auth

    @if ($user_profile->id != auth()->user()->id && $listCommonFriends->count() != 0)
        <div class="card mb-3">
            <div class="card-header">
                <a href="{{ route('friends', ['id' => $user_profile->id, 'section' => 'common']) }}"
                    class="link-body-emphasis">Общие друзья</a>
                <span class="text-secondary">{{ $listCommonFriends->count() }}</span>
            </div>
            <div class="card-body container text-center">
                <div class="row">
                    @foreach ($listCommonFriends->getRandomUsers(4) as $friend)
                        <div class="col p-2">
                            <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-emphasis"
                                href="{{ route('profile', $friend->id) }}">
                                <div class="w-100 position-relative">
                                    <img src="{{ $friend->getAvatar() }}" width="48" height="48"
                                        class="rounded-circle object-fit-cover" />
                                    @if ($friend->isOnline()['status'])
                                        @if (!$friend->isOnline()['mobile'])
                                            <span
                                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                            </span>
                                        @else
                                            <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="#198754" class="bi bi-phone" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                                </svg>
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
        @if ($listOnline->count() != 0)
            <div class="card mb-3">
                <div class="card-header">
                    <a href="{{ route('friends', ['id' => $user_profile->id, 'section' => 'online']) }}"
                        class="link-body-emphasis">Друзья онлайн</a>
                    <span class="text-secondary">{{ $listOnline->count() }}</span>
                </div>
                <div class="card-body container text-center">
                    <div class="row">
                        @foreach ($listOnline->getRandomUsers(4) as $friend)
                            <div class="col p-2">
                                <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-emphasis"
                                    href="{{ route('profile', $friend->id) }}">
                                    <div class="w-100 position-relative">
                                        <img src="{{ $friend->getAvatar() }}" width="48" height="48"
                                            class="rounded-circle object-fit-cover" />
                                        @if ($friend->isOnline()['status'])
                                            @if (!$friend->isOnline()['mobile'])
                                                <span
                                                    class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                                </span>
                                            @else
                                                <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="#198754" class="bi bi-phone" viewBox="0 0 16 16">
                                                        <path
                                                            d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                                    </svg>
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

    @if ($listFriends->count() != 0)
        <div class="card mb-3">
            <div class="card-header">
                @if ($user_profile == auth()->user())
                    <a href="{{ route('friends') }}" class="link-body-emphasis">Друзья</a>
                @else
                    <a href="{{ route('friends', ['id' => $user_profile->id]) }}" class="link-body-emphasis">Друзья</a>
                @endif
                <span class="text-secondary">{{ $listFriends->count() }}</span>
            </div>
            <div class="card-body container text-center">
                <div class="row">
                    @foreach ($listFriends->getRandomUsers(4) as $friend)
                        <div class="col p-2">
                            <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-emphasis"
                                href="{{ route('profile', $friend->id) }}">
                                <div class="w-100 position-relative">
                                    <img src="{{ $friend->getAvatar() }}" width="48" height="48"
                                        class="rounded-circle object-fit-cover" />
                                    @if ($friend->isOnline()['status'])
                                        @if (!$friend->isOnline()['mobile'])
                                            <span
                                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                            </span>
                                        @else
                                            <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="#198754" class="bi bi-phone" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                                </svg>
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
@endauth
