<div class="list-group list-group-flush h-100">
    @forelse ($listGroups as $group)
        <div class="list-group-item d-flex gap-2 w-100 pb-3 mb-1">
            <div>
                <a href="{{ route('groups.index', $group->id) }}">
                    @include('layouts.avatar', [
                        'model' => $group,
                        'width' => '80px',
                        'height' => '80px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false
                    ])
                </a>
            </div>
            <div>
                <a href="{{ route('groups.index', $group->id) }}" class="link-body-emphasis fw-bold link-underline-opacity-0">{{ $group->title }}</a>
                <p class="text-secondary m-0">{{ $group->theme }}</p>
                <p class="text-secondary m-0">{{ $group->members_count() }}</p>
            </div>
            <div class="flex-fill d-flex justify-content-end align-items-start">
                <div class="dropdown-center">
                    <a class="link-body-emphasis fw-bold link-underline-opacity-0" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{ route('groups.unsubscribe', $group->id) }}" method="post">
                                @csrf
                                <button class="dropdown-item" type="submit">Отписаться</button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    @empty
        <div class="text-center m-auto w-75">
            <div>Вы пока не состоите ни в одном сообществе.</div>
            <div>Вы можете
                <a data-bs-toggle="modal" data-bs-target="#creategroup" href="">создать сообщество</a>
                или воспользоваться
                <a href="#">поиском по сообществам</a>.
            </div>
        </div>
    @endforelse
</div>
