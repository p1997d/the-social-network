<div class="col d-lg-block d-none">
    <div class="card shadow position-sticky shadow" style="top: 5rem">
        <div class="card-body">
            <div class="list-group my-2">
                <a href="{{ route('groups.index', $group->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-center gap-2">
                        @include('layouts.avatar', [
                            'model' => $group,
                            'width' => '32px',
                            'height' => '32px',
                            'class' => 'rounded-circle object-fit-cover',
                            'modal' => false,
                        ])
                        <div>
                            <div class="fs-7">{{ $group->title }}</div>
                            <div class="fs-7 text-secondary">Вернуться к странице</div>
                        </div>

                    </div>
                </a>
            </div>
            <div class="list-group my-2">
                <a href="{{ route(\Route::currentRouteName(), [$group->id, 'act' => 'main']) }}" class="list-group-item list-group-item-action">Основные</a>
            </div>
            <div class="list-group my-2">
                <a href="{{ route(\Route::currentRouteName(),  [$group->id, 'act' => 'members']) }}" class="list-group-item list-group-item-action">Участники</a>
            </div>
        </div>
    </div>
</div>
