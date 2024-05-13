@foreach ($items as $post)
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
@endforeach
