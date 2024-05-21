<div class="d-flex flex-column">
    @auth
        <div class="card shadow mb-3">
            <div class="card-body">
                @include('layouts.forms.createPost', [
                    'recipientName' => 'user',
                    'recipientValue' => $user->id,
                    'contentPlaceholder' =>
                        auth()->user()->id == $user->id ? 'Что у вас нового?' : 'Напишите что-нибудь...',
                ])
            </div>
        </div>
    @endauth

    <div class="card shadow mb-3">
        <div class="card-body d-flex justify-content-between">
            <button class="btn btn-outline-secondary">Все записи</button>
        </div>
    </div>

    <div class="postsList">
        @forelse ($posts as $post)
            @include('layouts.post', $post)
        @empty
            <div class="card shadow m-0 emptyMessage">
                <div class="card-body">
                    <p class="text-center">На стене пока нет ни одной записи</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
