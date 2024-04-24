<form class="d-flex flex-fill searchForm" role="search" method="GET">
    <div class="input-group">
        <div class="input-group-text border-0 bg-transparent pe-0"><i class="bi bi-search"></i></div>
        @if (Request::query('to'))
            <input type="hidden" name="to" value="{{ Request::query('to') }}">
        @endif
        @if (Request::query('chat'))
            <input type="hidden" name="chat" value="{{ Request::query('chat') }}">
        @endif
        <input class="form-control me-2 border-0 searchForm bg-transparent" type="search" name="query"
            placeholder="Поиск" value="{{ $query ?? null }}" aria-label="Search" enterkeyhint="search">
    </div>
</form>
