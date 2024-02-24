<form class="d-flex flex-fill searchForm" role="search" method="GET">
    <div class="input-group">
        <div class="input-group-text border-0 bg-transparent pe-0"><i class="bi bi-search"></i></div>
        <input class="form-control me-2 border-0 searchForm bg-transparent" type="search" name="query"
            placeholder="Поиск" value="{{ $query ?? null }}" aria-label="Search" enterkeyhint="search">
    </div>
</form>
