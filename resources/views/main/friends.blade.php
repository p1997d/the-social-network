@extends('layouts.index')

@section('title', $title)

@section('content')
    <div class="col">
        <div class="card friends-pjax shadow">
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
                                    <img src="{{ $friend->avatar() }}" class="rounded-circle object-fit-cover"
                                        width="64" height="64" />

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
                                <div class="fs-5 fw-bold">
                                    <a href="{{ route('profile', $friend->id) }}" class="link-body-emphasis">
                                        {{ $friend->firstname }} {{ $friend->surname }}
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('messages', ['to' => $friend->id]) }}" class="fs-7">
                                        Написать сообщение
                                    </a>
                                    @foreach ($friend->friendForm() as $friendForm)
                                        <span>·</span>
                                        <form class="formFriends" method="POST" action="{{ $friendForm->link }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 fs-7">
                                                {{ $friendForm->title }}
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
