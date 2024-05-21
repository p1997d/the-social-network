<div class="card shadow mb-3">
    <div class="card-body">
        @include('layouts.forms.createPost', [
            'recipientName' => 'group',
            'recipientValue' => $group->id,
            'contentPlaceholder' => 'Что у вас нового?',
        ])
    </div>
</div>

<div class="card shadow mb-3">
    <div class="card-body d-flex justify-content-between">
        <button class="btn btn-outline-secondary">Записи сообщества</button>
    </div>
</div>

<div class="postsList">
    @forelse ($posts as $post)
        @include('layouts.post', $post)
    @empty
        <div class="card shadow mb-3">
            <div class="card-body">
                <p class="text-center">Новостей пока нет</p>
            </div>
        </div>
    @endforelse
</div>
