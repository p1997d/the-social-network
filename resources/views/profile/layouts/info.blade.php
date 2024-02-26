@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
    Carbon::setLocale('ru');
@endphp

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="fs-5">{{ $user_profile->firstname }} {{ $user_profile->surname }}</div>
        <div class="fs-7 text-secondary">
            {{ $user_profile->online()['online'] }}
            @if ($user_profile->online()['mobile'])
                <i class="bi bi-phone"></i>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="container addcolon">
            @foreach ($allInfo as $info)
                <div class="row">
                    <div class="col-6 col-lg-4">
                        <p class="text-secondary infotitle"><i class="bi {{ $info->icon }}"></i> {{ $info->title }}</p>
                    </div>
                    <div class="col">
                        <p>{{ $info->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
