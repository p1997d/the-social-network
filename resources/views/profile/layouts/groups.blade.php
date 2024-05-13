@if ($user->groups->count() > 0)
    <div class="card shadow">
        <div class="card-header">
            <a href="{{ route('groups.list') }}" class="link-body-emphasis">Группы</a>
            <span class="text-secondary">{{ $user->groups->count() }}</span>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                @foreach ($user->groups->take(5) as $group)
                    <a href="{{ route('groups.index', $group->id) }}"
                        class="list-group-item d-flex w-100 align-items-center list-group-item-action rounded gap-2">
                        @include('layouts.avatar', [
                            'model' => $group,
                            'width' => '48px',
                            'height' => '48px',
                            'class' => 'rounded-circle object-fit-cover',
                            'modal' => false,
                        ])
                        <span>{{ $group->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
