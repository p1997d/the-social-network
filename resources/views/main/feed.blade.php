@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-3">
                <div class="card-header d-flex gap-2 align-items-center">
                    <div>
                        @include('layouts.avatar', [
                            'model' => $user,
                            'width' => '32px',
                            'height' => '32px',
                            'class' => 'rounded-circle object-fit-cover',
                            'modal' => false,
                        ])
                    </div>
                    <div class="flex-fill">
                        @include('layouts.forms.createPost', [
                            'recipientName' => 'user',
                            'recipientValue' => $user->id,
                            'contentPlaceholder' => 'Что у вас нового?',
                        ])
                    </div>
                </div>
            </div>

            @forelse ($posts as $post)
                @if ($post->group)
                    @include('layouts.post', [
                        'post' => $post,
                        'postHeaderLink' => route('groups.index', $post->group->id),
                        'postHeaderAvatar' => $post->group,
                        'postHeaderTitle' => $post->group->title,
                        'postAdminCondition' => $post->group->admins()->contains('id', auth()->user()->id),
                    ])
                @else
                    @include('layouts.post', [
                        'post' => $post,
                        'postHeaderLink' => route('profile', $post->authorUser->id),
                        'postHeaderAvatar' => $post->authorUser,
                        'postHeaderTitle' => $post->authorUser->firstname . ' ' . $post->authorUser->surname,
                        'postAdminCondition' => optional(auth()->user())->id == $user->id,
                    ])
                @endif

            @empty
                <div class="card shadow m-0">
                    <div class="card-body">
                        <p class="text-center">Новостей пока нет</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="col-lg-4">
            <div class="col d-lg-block d-none">
                <div class="card shadow position-sticky shadow" style="top: 5rem">
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('feed') }}" class="list-group-item list-group-item-action">Новости</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
