@if (\Route::currentRouteName() === 'search.all')
    <div class="d-flex justify-content-between">
        <h5>{{ $title }}</h5>
        <a href="{{ route($link, ['query' => $query]) }}" class="link-body-emphasis">{{ $linkTitle }}</a>
    </div>
@endif
