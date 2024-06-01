<div class="card-header d-flex gap-2">
    <div>
        @include('layouts.avatar', [
            'model' => $group,
            'width' => '45px',
            'height' => '45px',
            'class' => 'rounded-circle object-fit-cover',
            'modal' => true,
        ])
    </div>
    <div>
        <p class="fw-bold m-0">{{ $group->title }}</p>
        @if ($group->ifSubscribed())
            <p class="text-secondary m-0 fs-7"><i class="bi bi-check2"></i> Вы подписаны</p>
        @else
            <p class="text-secondary m-0 fs-7">{{ $group->members_count() }}</p>
        @endif
    </div>
    <div class="flex-fill d-flex justify-content-end align-items-center gap-2">
        @if ($group->ifSubscribed())
            <form action="{{ route('groups.unsubscribe', $group->id) }}" method="post">
                @csrf
                <button class="btn btn-secondary btn-sm" type="submit">Отписаться</button>
            </form>
        @else
            <form action="{{ route('groups.subscribe', $group->id) }}" method="post">
                @csrf
                <button class="btn btn-primary btn-sm" type="submit">Подписаться</button>
            </form>
        @endif

        @if ($group->admins()->contains('id', optional(auth()->user())->id))
            <a href="{{ route('groups.index.settings', $group->id) }}" class="btn btn-secondary btn-sm" type="submit">Управление</a>
        @endif
    </div>
</div>
