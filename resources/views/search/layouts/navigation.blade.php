<div class="col-lg-4">
    <div class="col d-lg-block d-none">
        <div class="card shadow position-sticky shadow" style="top: 5rem">
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('search.index', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Все</a>
                    <a href="{{ route('search.people', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Люди</a>
                    <a href="{{ route('search.news', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Новости</a>
                    <a href="{{ route('search.group', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Группы</a>
                    <a href="{{ route('search.music', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Музыка</a>
                    <a href="{{ route('search.video', ['query' => $query]) }}"
                        class="list-group-item list-group-item-action">Видео</a>
                </div>
            </div>
        </div>
    </div>
</div>
