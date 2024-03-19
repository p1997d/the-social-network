@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

<div class="list-group list-group-flush w-100 h-100">
    @forelse ($chatlogs as $chatlog)
        <a href="{{ route('messages', [class_basename($chatlog) == 'User' ? 'to' : 'chat' => $chatlog->id]) }}"
            class="list-group-item list-group-item-action d-flex gap-2">
            <div class="position-relative">
                @include('layouts.avatar', [
                    'model' => $chatlog,
                    'width' => '64px',
                    'height' => '64px',
                    'class' => 'rounded-circle object-fit-cover',
                    'modal' => false
                ])
                @if (class_basename($chatlog) == 'User')
                    @if (optional($chatlog->online())['status'])
                        @if (!$chatlog->online()['mobile'])
                            <span
                                class="onlineBadge position-absolute badge bg-success p-2 border border-3 rounded-circle">
                            </span>
                        @else
                            <span class="onlineBadge position-absolute bg-body rounded-circle p-1 lh-1 text-success">
                                <i class="bi bi-phone"></i>
                            </span>
                        @endif
                    @endif
                @endif
            </div>
            <div class="w-100 d-flex flex-column justify-content-evenly">
                <div class="d-flex justify-content-between w-100">
                    @if (class_basename($chatlog) == 'User')
                        <div class="fw-bold">{{ $chatlog->firstname }} {{ $chatlog->surname }}</div>
                    @else
                        <div class="fw-bold">{{ $chatlog->title }}</div>
                    @endif
                    <div class="text-secondary fs-7">
                        {{ Carbon::parse($chatlog->lastMessage()->sent_at)->diffForHumans() }}
                    </div>
                </div>
                <div class="d-flex justify-content-between w-100">
                    <div class="m-0 d-flex gap-2">
                        @if (class_basename($chatlog->lastMessage()) !== 'ChatSystemMessage' &&
                                $chatlog->lastMessage()->senderUser->id == auth()->user()->id)
                            <div>
                                @include('layouts.avatar', [
                                    'model' => $chatlog->lastMessage()->senderUser,
                                    'width' => '25px',
                                    'height' => '25px',
                                    'class' => 'rounded-circle object-fit-cover',
                                    'modal' => false
                                ])
                            </div>
                        @endif
                        <div class="text-break text-secondary text-short">
                            @if (class_basename($chatlog->lastMessage()) == 'ChatSystemMessage')
                                {{ $chatlog->lastMessage()->senderUser->firstname }}
                                {{ $chatlog->lastMessage()->senderUser->surname }}
                                {{ $chatlog->lastMessage()->content }}
                            @else
                                @if (Crypt::decrypt($chatlog->lastMessage()->content))
                                    {{ Crypt::decrypt($chatlog->lastMessage()->content) }}
                                @else
                                    <i>Файлов:
                                        {{ count(json_decode($chatlog->lastMessage()->attachments)) }}</i>
                                @endif
                            @endif

                        </div>
                    </div>
                    <div class="fs-7">
                        @if ($chatlog->unreadMessagesCount() > 0)
                            <span
                                class="badge rounded-circle text-bg-primary">{{ $chatlog->unreadMessagesCount() }}</span>
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
