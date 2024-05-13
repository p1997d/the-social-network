<a href="{{ route('friends', ['id' => request('id')]) }}"
    class="btn btn-emphasis btn-sm @if (!$section) active @endif">
    Все друзья
    <span class="text-secondary">{{ $listFriends->count() }}</span>
</a>
@if ($listCommonFriends)
    <a href="{{ route('friends', ['section' => 'common', 'id' => request('id')]) }}"
        class="btn btn-emphasis btn-sm @if ($section == 'common') active @endif">
        Общие друзья
        <span class="text-secondary">{{ $listCommonFriends->count() }}</span>
    </a>
@endif
@if ($listOnline)
    <a href="{{ route('friends', ['section' => 'online', 'id' => request('id')]) }}"
        class="btn btn-emphasis btn-sm @if ($section == 'online') active @endif">
        Друзья онлайн
        <span class="text-secondary">{{ $listOnline->count() }}</span>
    </a>
@endif
@if ($user == auth()->user())
    @if ($listOutgoing->count() > 0)
        <a href="{{ route('friends', ['section' => 'outgoing', 'id' => request('id')]) }}"
            class="btn btn-emphasis btn-sm @if ($section == 'outgoing') active @endif">
            Исходящие заявки
            <span class="text-secondary">{{ $listOutgoing->count() }}</span>
        </a>
    @endif
    @if ($listIncoming->count() > 0)
        <a href="{{ route('friends', ['section' => 'incoming', 'id' => request('id')]) }}"
            class="btn btn-emphasis btn-sm @if ($section == 'incoming') active @endif">
            Входящие заявки
            <span class="text-secondary">{{ $listIncoming->count() }}</span>
        </a>
    @endif
@endif
