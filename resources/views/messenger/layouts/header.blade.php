<div class="position-fixed z-3 messages-header">
    <div class="justify-content-between bg-body-tertiary p-2 border rounded-top main-message-header d-flex">
        <div>
            <a href="{{ route('messages') }}" class="btn btn-text px-0">
                <i class="bi bi-chevron-left"></i> Назад
            </a>
        </div>
        <div class="text-center fs-7 d-flex flex-column justify-content-center">
            @if (class_basename($recipient) == 'User')
                <a href="{{ route('profile', $recipient->id) }}" class="link-body-emphasis">
                    @if ($recipient->id != auth()->id())
                        {{ $recipient->firstname }} {{ $recipient->surname }}
                    @else
                        Избранное
                    @endif
                </a>
                @if ($recipient->id != auth()->id())
                    <p class="m-0 text-secondary">{{ $recipient->online()['online'] }}</p>
                @endif
            @else
                <a href="#" class="link-body-emphasis">
                    {{ $recipient->title }}
                </a>
                <p class="m-0 text-secondary">{{$countMembers}}</p>
            @endif
        </div>
        <div>
            <div class="dropdown">
                <button class="btn btn-text" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button type="button" class="dropdown-item search-button">
                            <i class="bi bi-search"></i> Поиск
                        </button>
                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#allDeleteModal">
                            <i class="bi bi-trash"></i> Очистить историю сообщений
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div
        class="justify-content-between align-items-center bg-body-tertiary p-2 border rounded-top search-message-header d-none">
        @include('messenger.layouts.search')
        <button type="button" class="btn-close close-search-button" aria-label="Close"></button>
    </div>
</div>
