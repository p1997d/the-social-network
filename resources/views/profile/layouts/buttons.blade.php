<div class="d-flex flex-column gap-3 buttons-pjax">
    @auth
        @if ($user_profile->id == auth()->user()->id)
            <a href="{{ route('info.editprofile') }}" class="btn btn-primary w-100">Редактировать</a>
        @else
            <a href="{{ route('messages', ['to' => $user_profile->id]) }}" class="btn btn-primary">Отправить сообщение</a>
            @foreach ($user_profile->getFriendsForms() as $form)
                <form class="w-100 formFriends" method="POST"
                    action="{{ $form->link }}">
                    @csrf
                    <button type="submit" class="btn {{ $form->color }} w-100">
                        <i class="bi {{ $form->icon }}"></i>
                        {{ $form->title }}
                    </button>
                </form>
            @endforeach
        @endif
    @endauth
</div>
