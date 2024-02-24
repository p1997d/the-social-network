@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
    Carbon::setLocale('ru');
@endphp

<div
    class="list-group-item list-group-item-action list-group-item-empty gap-2 message justify-content-center rounded border-0 rounded-0 d-none">
    <div>
        <a href="" class="profileImageLink">
            <img src="" width="36" height="36" class="rounded-circle object-fit-cover">
        </a>
    </div>
    <div class="w-100">
        <div class="d-flex justify-content-between w-100">
            <div>
                <a href="" class="profileNameLink"></a>
                <span class="text-secondary fs-7 sent-at"></span>
            </div>
            <div class="message-buttons">
                <button class="btn btn-text text-emphasis fs-7 px-1 py-0 editButton" data-bs-messageid="">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-text text-emphasis fs-7 px-1 py-0 deleteModal" data-bs-toggle="modal"
                    data-bs-target="#deleteModal" data-bs-messageid="" data-bs-mymessage="">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="m-0 text-break content">
            <div class="container text-center attachments"></div>
        </div>
    </div>
</div>

@foreach ($messages as $i => $message)
    @if (class_basename($message) == 'ChatSystemMessage')
        <div class="list-group-item list-group-item-message text-center text-secondary border-0">
            <p>
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
        <div class="list-group-item list-group-item-action list-group-item-message d-flex gap-2 message justify-content-center rounded border-0 @if ($message->senderUser->id != auth()->user()->id && !$message->viewed_at) unread @endif rounded-0"
            id="{{ $message->id }}">
            <div>
                <a href="{{ route('profile', $message->senderUser->id) }}" class="profileImageLink">
                    <img src="{{ $message->senderUser->getAvatar() }}" width="36" height="36"
                        class="rounded-circle object-fit-cover">
                </a>
            </div>
            <div class="w-100">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <a href="{{ route('profile', $message->senderUser->id) }}" class="profileNameLink">
                            {{ $message->senderUser->firstname }}</a>
                        <span class="text-secondary fs-7 sent-at" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="{{ Carbon::parse($message->sent_at)->isoFormat('LL LTS') }}">
                            {{ Carbon::parse($message->sent_at)->diffForHumans() }}
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

                    @if ($message->changed_at && $message->sent_at != $message->changed_at)
                        <span class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="изменено {{ Carbon::parse($message->changed_at)->diffForHumans() }}">(ред.)</span>
                        </span>
                    @endif

                    @include('messenger.layouts.attachments')
                </div>
            </div>
        </div>
    @endif

    @if (
        $loop->last ||
            (isset($messages[$i + 1]) &&
                !Carbon::parse($messages[$i + 1]->sent_at)->isSameDay(Carbon::parse($message->sent_at))))
        <div class="list-group-item text-center border-0 bg-transparent text-secondary">
            {{ $message->getDate() }}
        </div>
    @endif
@endforeach
