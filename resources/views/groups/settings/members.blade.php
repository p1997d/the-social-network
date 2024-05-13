@extends('groups.settings.layouts.index')

@section('groupSettingsTitle', 'Участники')

@section('groupSettingsBody')
    <ul class="list-group">
        @foreach ($group->members()->get() as $member)
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    @include('layouts.avatar', [
                        'model' => $member,
                        'width' => '32px',
                        'height' => '32px',
                        'class' => 'rounded-circle object-fit-cover',
                        'modal' => false,
                    ])
                    {{ $member->firstname }} {{ $member->surname }}
                    @if ($group->author === $member->id)
                        <span class="text-secondary fs-7">(Создатель)</span>
                    @elseif ($group->isAdmin($member))
                        <span class="text-secondary fs-7">(Администратор)</span>
                    @endif
                </div>
                <div class="d-flex gap-1">
                    @if ($group->author !== $member->id)
                        <form action="{{ route('groups.switchAdmin', $group->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="user", value="{{ $member->id }}">
                            @if ($group->isAdmin($member))
                                <button type="submit" class="btn btn-secondary btn-sm">Разжаловать</button>
                            @else
                                <button type="submit" class="btn btn-secondary btn-sm">Назначить руководителем</button>
                            @endif
                        </form>

                        @if ((auth()->user()->id !== $member->id && !$group->isAdmin($member)) || $group->author === auth()->user()->id)
                            <form action="{{ route('groups.kick', $group->id) }}" method="post">
                                @csrf
                                <input type="hidden" name="user", value="{{ $member->id }}">
                                <button type="submit" class="btn btn-secondary btn-sm">Выгнать</button>
                            </form>
                        @endif
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
@endsection
