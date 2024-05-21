@foreach ($messages as $i => $message)
    @if (class_basename($message) == 'ChatSystemMessage')
        <div class="list-group-item list-group-item-message text-center text-secondary border-0">
            <p class="mx-auto">
                <a href="{{ route('profile', $message->senderUser->id) }}"
                    class="fw-bold link-secondary link-underline-opacity-0">
                    {{ $message->senderUser->firstname }} {{ $message->senderUser->surname }}
                </a>
                {{ $message->content }}
                @if ($message->recipientUser)
                    <a href="{{ route('profile', $message->recipientUser->id) }}"
                        class="fw-bold link-secondary link-underline-opacity-0">
                        {{ $message->recipientUser->firstname }} {{ $message->recipientUser->surname }}
                    </a>
                @endif

            </p>
        </div>
    @else
        <div class="list-group-item list-group-item-action list-group-item-message gap-2 message justify-content-center rounded border-0 rounded-0 @if ($message->senderUser->id !== auth()->user()->id && !$message->viewed_at) unread @endif"
            id="{{ $message->id }}">
            <div>
                <a href="{{ route('profile', $message->senderUser->id) }}" class="profileImageLink">
                    @include('layouts.avatar', [
                        'model' => $message->senderUser,
                        'width' => '36px',
                        'height' => '36px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false,
                    ])
                </a>
            </div>
            <div class="w-100">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <a href="{{ route('profile', $message->senderUser->id) }}" class="profileNameLink">
                            {{ $message->senderUser->firstname }}</a>
                        <span class="text-secondary fs-7 sent-at" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            data-bs-custom-class="custom-tooltip" data-bs-title="{{ $message->sentAtIsoFormat() }}">
                            {{ $message->sentAtDiffForHumans() }}
                        </span>
                    </div>
                    <div class="message-buttons">
                        @if ($message->senderUser->id === auth()->user()->id)
                            <button class="btn btn-text text-emphasis fs-7 px-1 py-0 editButton"
                                data-bs-messageid="{{ $message->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                        @endif
                        <button class="btn btn-text text-emphasis fs-7 px-1 py-0 deleteModal" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-bs-messageid="{{ $message->id }}"
                            data-bs-mymessage="{{ $message->senderUser->id === auth()->user()->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="m-0 text-break content">
                    {{ Crypt::decrypt($message->content) }}

                    @if ($message->changed_at && $message->sent_at !== $message->changed_at)
                        <span class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="изменено {{ $message->changedAtDiffForHumans() }}">(ред.)</span>
                        </span>
                    @endif

                    @include('layouts.attachments', [
                        'model' => $message,
                        'group' => 'messages',
                    ])
                </div>
            </div>
        </div>
    @endif

    @if ($loop->last || (isset($messages[$i + 1]) && !$messages[$i + 1]->sentTheSameDay($message)))
        <div class="list-group-item text-center border-0 bg-transparent text-secondary">
            {{ $message->date() }}
        </div>
    @endif
@endforeach
