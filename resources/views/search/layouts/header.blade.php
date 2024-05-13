<form class="d-lg-flex d-none" role="search" method="GET" action="{{ route(\Route::currentRouteName()) }}">
    <div class="input-group">
        <div class="input-group-text border-end-0 bg-body pe-0"><i class="bi bi-search"></i></div>
        <input class="form-control me-2 border-start-0 mainSearchForm" type="search" name="query" placeholder="Поиск"
            aria-label="Search" enterkeyhint="search" value="{{ $query }}">
    </div>
</form>
