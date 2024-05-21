<div class="card shadow">
    <div class="card-body">
        <div class="avatar mb-3 d-flex justify-content-center">
            <div class="position-relative" id="avatar">
                @auth
                    @if ($user->id == auth()->user()->id)
                        <div class="collapse position-absolute w-100" id="avatarSetting">
                            <div class="list-group bg-body bg-opacity-50">
                                <button type="button" class="list-group-item list-group-item-action bg-transparent"
                                    data-bs-toggle="modal" data-bs-target="#updateAvatar">
                                    <i class="bi bi-pencil"></i>
                                    Обновить фотографию
                                </button>
                                <form method="POST" action="{{ route('info.deleteAvatar') }}">
                                    @csrf
                                    <button class="list-group-item list-group-item-action bg-transparent rounded-bottom"
                                        type="submit"><i class="bi bi-trash text-danger"></i>
                                        Удалить фотографию
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth

                <div data-bs-toggle="collapse">
                    @include('layouts.avatar', [
                        'model' => $user,
                        'width' => '350px',
                        'class' => 'rounded object-fit-cover mw-100',
                        'modal' => true,
                    ])
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 buttons-pjax">
            @auth
                @if ($user->id == auth()->user()->id)
                    <a href="{{ route('info.editProfile') }}" class="btn btn-primary w-100">Редактировать</a>
                @else
                    <a href="{{ route('messages', ['to' => $user->id]) }}" class="btn btn-primary">Отправить
                        сообщение</a>
                    @include('layouts.forms.friends', [
                        'friendForm' => $friendForm,
                        'buttons' => true,
                    ])
                @endif
            @endauth
        </div>

    </div>
</div>
