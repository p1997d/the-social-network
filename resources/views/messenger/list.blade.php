@extends('layouts.index')

@section('title', 'Сообщения')

@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

@section('content')
    <div class="col-lg-7">
        <div class="card h-100 shadow">
            <div class="card-header d-flex justify-content-between">
                @include('messenger.layouts.search')
                <div>
                    <button class="btn btn-text" data-bs-toggle="modal" data-bs-target="#chatCreateModal"><i
                            class="bi bi-pencil-square"></i></button>
                </div>
            </div>
            <div class="card-body h-100 messages-pjax">
                <div class="list-group list-group-flush w-100 h-100">
                    @forelse ($chatlogs as $chatlog)
                        <a href="{{ route('messages', [$chatlog->type => $chatlog->id]) }}"
                            class="list-group-item list-group-item-action d-flex gap-2">
                            <div class="position-relative">
                                <img src="{{ $chatlog->avatar }}" class="rounded-circle object-fit-cover" width="64"
                                    height="64" />

                                @if (optional($chatlog->online)['status'])
                                    @if (!$chatlog->online['mobile'])
                                        <span
                                            class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                                        </span>
                                    @else
                                        <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 text-success">
                                            <i class="bi bi-phone"></i>
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-evenly">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="fw-bold">{{ $chatlog->title }}</div>
                                    <div class="text-secondary fs-7">
                                        {{ Carbon::parse($chatlog->lastMessage->sent_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="m-0 d-flex gap-2">
                                        @if (class_basename($chatlog->lastMessage) != 'ChatSystemMessage' &&
                                                $chatlog->lastMessage->senderUser->id == auth()->user()->id)
                                            <div>
                                                <img src="{{ $chatlog->lastMessage->senderUser->avatar() }}"
                                                    class="rounded-circle object-fit-cover" width="25" height="25" />
                                            </div>
                                        @endif
                                        <div class="text-break text-secondary text-short">
                                            @if (class_basename($chatlog->lastMessage) == 'ChatSystemMessage')
                                                {{ $chatlog->lastMessage->senderUser->firstname }}
                                                {{ $chatlog->lastMessage->senderUser->surname }}
                                                {{ $chatlog->lastMessage->content }}
                                            @else
                                                @if (Crypt::decrypt($chatlog->lastMessage->content))
                                                    {{ Crypt::decrypt($chatlog->lastMessage->content) }}
                                                @else
                                                    <i>Файлов:
                                                        {{ count(json_decode($chatlog->lastMessage->attachments)) }}</i>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                    <div class="fs-7">
                                        @if ($chatlog->unreadMessagesCount > 0)
                                            <span
                                                class="badge rounded-circle text-bg-primary">{{ $chatlog->unreadMessagesCount }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center my-auto">
                            <p>Список чатов пуст</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @include('messenger.modals.chatCreate')
    @include('messenger.layouts.navigation')
@endsection
