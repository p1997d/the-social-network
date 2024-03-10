<div class="avatar mb-3 d-flex justify-content-center">
    <div class="position-relative" id="avatar">
        @auth
            @if ($user_profile->id == auth()->user()->id)
                <div class="collapse position-absolute w-100" id="avatarSetting">
                    <div class="list-group bg-body bg-opacity-50">
                        <button type="button" class="list-group-item list-group-item-action bg-transparent"
                            data-bs-toggle="modal" data-bs-target="#updateavatar">
                            <i class="bi bi-pencil"></i>
                            Обновить фотографию
                        </button>
                        <form method="POST" action="{{ route('info.deleteavatar') }}">
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
                'model' => $user_profile,
                'width' => '350px',
                'class' => 'rounded object-fit-cover',
                'modal' => true
            ])
        </div>
    </div>
</div>
