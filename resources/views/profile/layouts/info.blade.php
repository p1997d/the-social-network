@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
@endphp

<div class="card shadow">
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
        <table class="table table-borderless">
            <tbody>
                @foreach ($allInfo as $info)
                    <tr>
                        <th scope="row" class="text-secondary col-4">
                            <i class="bi {{ $info->icon }}"></i>
                            {{ $info->title }}:
                        </th>
                        <td>{{ $info->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
