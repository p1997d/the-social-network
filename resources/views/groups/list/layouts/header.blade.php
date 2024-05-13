<div class="d-flex justify-content-between gap-3">
    <div class="d-flex gap-2">
        <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id]) }}"
            class="btn btn-emphasis btn-sm @if (!$tab) active @endif">
            Все группы
            <span class="text-secondary">{{ $groups->count() }}</span>
        </a>
        <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'tab' => 'admin']) }}"
            class="btn btn-emphasis btn-sm @if ($tab == 'admin') active @endif">
            Управление
            <span class="text-secondary">{{ $administeredGroups->count() }}</span>
        </a>
    </div>
    <div class="flex-fill d-flex justify-content-end">
        @if (auth()->user()->id == $user->id)
            <button class="btn btn-emphasis-invert btn-sm" data-bs-toggle="modal" data-bs-target="#creategroup">
                Создать группу
            </button>
        @endif
    </div>
</div>
