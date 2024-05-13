<div class="list-group list-group-flush h-100">
    @foreach ($items as $group)
        <div class="list-group-item d-flex gap-2 w-100 pb-3 mb-1">
            <div>
                <a href="{{ route('groups.index', $group->id) }}">
                    @include('layouts.avatar', [
                        'model' => $group,
                        'width' => '80px',
                        'height' => '80px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false,
                    ])
                </a>
            </div>
            <div>
                <a href="{{ route('groups.index', $group->id) }}"
                    class="link-body-emphasis fw-bold link-underline-opacity-0">{{ $group->title }}</a>
                <p class="text-secondary m-0">{{ $group->theme }}</p>
                <p class="text-secondary m-0">{{ $group->members_count() }}</p>
            </div>
        </div>
    @endforeach
</div>
