<div class="list-group list-group-flush w-100 h-100">
    @forelse ($chatLogs as $chatLog)
        <a href="@if (class_basename($chatLog) == 'Dialog') {{ route('messages', ['to' => $chatLog->interlocutor->id]) }} @else {{ route('messages', ['chat' => $chatLog->id]) }} @endif"
            class="list-group-item list-group-item-action d-flex gap-2">
            <div class="position-relative">
                @include('layouts.avatar', [
                    'model' => class_basename($chatLog) == 'Dialog' ? $chatLog->interlocutor : $chatLog,
                    'width' => '64px',
                    'height' => '64px',
                    'class' => 'rounded-circle object-fit-cover',
                    'modal' => false,
                ])
                @if (class_basename($chatLog) == 'Dialog')
                    @if (optional($chatLog->interlocutor->online())['status'])
                        @if (!$chatLog->interlocutor->online()['mobile'])
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
                    @if (class_basename($chatLog) == 'Dialog')
                        <div class="fw-bold">{{ $chatLog->interlocutor->firstname }}
                            {{ $chatLog->interlocutor->surname }}</div>
                    @else
                        <div class="fw-bold">{{ $chatLog->title }}</div>
                    @endif
                    <div class="text-secondary fs-7">
                        {{ $chatLog->messages()->last()->sentAtDiffForHumans() }}
                    </div>
                </div>
                <div class="d-flex justify-content-between w-100">
                    <div class="m-0 d-flex gap-2">
                        @if (class_basename($chatLog->messages()->last()) !== 'ChatSystemMessage' && $chatLog->messages()->last()->senderUser->id == auth()->user()->id || class_basename($chatLog) === 'Chat')
                            <div>
                                @include('layouts.avatar', [
                                    'model' => $chatLog->messages()->last()->senderUser,
                                    'width' => '25px',
                                    'height' => '25px',
                                    'class' => 'rounded-circle object-fit-cover',
                                    'modal' => false,
                                ])
                            </div>
                        @endif
                        <div class="text-break text-secondary text-short">
                            @if (class_basename($chatLog->messages()->last()) == 'ChatSystemMessage')
                                {{ $chatLog->messages()->last()->senderUser->firstname }}
                                {{ $chatLog->messages()->last()->senderUser->surname }}
                                {{ $chatLog->messages()->last()->content }}
                            @else
                                @if (Crypt::decrypt($chatLog->messages()->last()->content))
                                    {{ Crypt::decrypt($chatLog->messages()->last()->content) }}
                                @else
                                    <i>Файлов:
                                        {{ count(json_decode($chatLog->messages()->last()->attachments())) }}</i>
                                @endif
                            @endif

                        </div>
                    </div>
                    <div class="fs-7">
                        @if ($chatLog->unreadMessagesCount() > 0)
                            <span
                                class="badge rounded-circle text-bg-primary">{{ $chatLog->unreadMessagesCount() }}</span>
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
