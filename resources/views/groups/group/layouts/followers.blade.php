@if ($friends)
    <div class="card shadow mb-3">
        <div class="card-header">
            {{ $friends }}
        </div>
    </div>
@endif

<div class="card shadow mb-3">
    <div class="card-header">
        <p class="m-0">Участники <span class="text-secondary">{{ $group->members()->count() }}</span></p>
    </div>
    <div class="card-body container text-center">
        <div class="row">
            @foreach ($group->members()->getRandomUsers(6) as $member)
                <div class="col p-2">
                    <a class="link-body-emphasis link-underline link-underline-opacity-0 btn btn-link-emphasis"
                        href="{{ route('profile', $member->id) }}">
                        <div class="w-100 position-relative">
                            @include('layouts.avatar', [
                                'model' => $member,
                                'width' => '48px',
                                'height' => '48px',
                                'class' => 'rounded-circle object-fit-cover',
                                'modal' => false,
                            ])
                        </div>
                        <div class="title text-center">{{ $member->firstname }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
