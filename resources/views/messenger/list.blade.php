@extends('layouts.index')

@section('title', 'Сообщения')

@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

@section('content')
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                @include('messenger.layouts.search')
                <div>
                    <button class="btn btn-text" data-bs-toggle="modal" data-bs-target="#chatCreateModal"><i
                            class="bi bi-pencil-square"></i></button>
                </div>
            </div>
            <div class="card-body h-100 messages-pjax">
                <div class="list-group list-group-flush w-100 h-100">
                    @forelse ($messages as $message)
                        <a href="{{ route('messages', [$message->type => $message->id]) }}"
                            class="list-group-item list-group-item-action d-flex gap-2">
                            <div class="position-relative">
                                <img src="{{ $message->avatar }}" class="rounded-circle object-fit-cover" width="64"
                                    height="64" />

                                @if (optional($message->online)['status'])
                                    @if (!$message->online['mobile'])
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
                            <div class="w-100 d-flex flex-column justify-content-evenly">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="fw-bold">{{ $message->title }}</div>
                                    <div class="text-secondary fs-7">
                                        {{ Carbon::parse($message->lastMessage->sent_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="m-0 d-flex gap-2">
                                        @if (class_basename($message->lastMessage) != 'ChatSystemMessage' &&
                                                $message->lastMessage->senderUser->id == auth()->user()->id)
                                            <div>
                                                <img src="{{ $message->lastMessage->senderUser->getAvatar() }}"
                                                    class="rounded-circle object-fit-cover" width="25" height="25" />
                                            </div>
                                        @endif
                                        <div class="text-break text-secondary text-short">
                                            @if (class_basename($message->lastMessage) == 'ChatSystemMessage')
                                                {{ $message->lastMessage->senderUser->firstname }}
                                                {{ $message->lastMessage->senderUser->surname }}
                                                {{ $message->lastMessage->content }}
                                            @else
                                                @if (Crypt::decrypt($message->lastMessage->content))
                                                    {{ Crypt::decrypt($message->lastMessage->content) }}
                                                @else
                                                    <i>Файлов:
                                                        {{ count(json_decode($message->lastMessage->attachments)) }}</i>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                    <div class="fs-7">
                                        {{-- @if ($message->getMessageCount() > 0)
                                            <span
                                                class="badge rounded-circle text-bg-primary">{{ $message->getMessageCount() }}</span>
                                        @endif --}}
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
