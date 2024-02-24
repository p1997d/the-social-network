@extends('layouts.index')

@section('title', $title)

@section('content')
    <div class="col">
        <div class="card friends-pjax">
            <div class="card-header">
                <p class="fs-4 d-none d-lg-block border-bottom pb-3">{{ $title }}</p>
                <a href="{{ route('friends', ['id' => request('id')]) }}" class="btn btn-outline-secondary">
                    Все друзья
                    {{ $listFriends->count() }}
                </a>
                @if ($listCommonFriends)
                    <a href="{{ route('friends', ['section' => 'common', 'id' => request('id')]) }}"
                        class="btn btn-outline-secondary">
                        Общие друзья {{ $listCommonFriends->count() }}
                    </a>
                @endif
                @if ($listOnline)
                    <a href="{{ route('friends', ['section' => 'online', 'id' => request('id')]) }}"
                        class="btn btn-outline-secondary">
                        Друзья онлайн {{ $listOnline->count() }}
                    </a>
                @endif
                @if ($user_profile == auth()->user())
                    @if ($listOutgoing->count() > 0)
                        <a href="{{ route('friends', ['section' => 'outgoing', 'id' => request('id')]) }}"
                            class="btn btn-outline-secondary">
                            Исходящие заявки {{ $listOutgoing->count() }}
                        </a>
                    @endif
                    @if ($listIncoming->count() > 0)
                        <a href="{{ route('friends', ['section' => 'incoming', 'id' => request('id')]) }}"
                            class="btn btn-outline-secondary">
                            Входящие заявки {{ $listIncoming->count() }}
                        </a>
                    @endif
                @endif
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse ($friends as $friend)
                        <li class="list-group-item border-0 d-flex gap-2 px-0">
                            <div class="position-relative">
                                <a href="{{ route('profile', $friend->id) }}">
                                    <img src="{{ $friend->getAvatar() }}" class="rounded-circle object-fit-cover"
                                        width="64" height="64" />

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
                                </a>
                            </div>
                            <div class="w-100 border-bottom">
                                <div class="fs-5 fw-bold">
                                    <a href="{{ route('profile', $friend->id) }}" class="link-body-emphasis">
                                        {{ $friend->firstname }} {{ $friend->surname }}
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('messages', ['to' => $friend->id]) }}" class="fs-7">
                                        Написать сообщение
                                    </a>
                                    @foreach ($friend->getFriendsForms() as $form)
                                        <span>·</span>
                                        <form class="formFriends" method="POST" action="{{ $form->link }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 fs-7">
                                                {{ $form->title }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @empty
                        <div class="text-center">
                            <p>Ни одного друга не найдено</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
