@extends('layouts.index')

@section('title', 'Редактирование профиля')

@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

@section('css')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('js')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>

    <script src="{{ asset('js/regionPicker.js') }}"></script>
@endsection

@section('content')
    <div class="col">
        <div class="card">
            <div class="card-header">
                <span class="fs-4">Профиль</span>
            </div>
            <div class="card-body addcolon">
                @include('layouts.alerts')
                <form method="POST" action="{{ route('info.updateprofile') }}">
                    @csrf
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Имя</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="colFormLabelSm" name="firstname"
                                value="{{ auth()->user()->firstname }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Фамилия</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="colFormLabelSm" name="surname"
                                value="{{ auth()->user()->surname }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Пол</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="sex">
                                <option selected disabled>Не выбрано</option>
                                <option value="male" {{ auth()->user()->sex == 'male' ? 'selected' : '' }}>Мужской
                                </option>
                                <option value="female" {{ auth()->user()->sex == 'female' ? 'selected' : '' }}>Женский
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="InputBirth" class="col-sm-3 col-form-label">День рождения</label>
                        <div class="col-sm-9 d-flex justify-content-between gap-2" id="InputBirth">
                            <div class="w-100">
                                <select class="form-select" id="InputDay" name="birthDay" required autofocus>
                                    <option selected disabled>День</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}"
                                            {{ Carbon::parse(auth()->user()->birth)->day == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="w-100">
                                <select class="form-select" id="InputMonth" name="birthMonth" required autofocus>
                                    <option selected disabled>Месяц</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ Carbon::parse(auth()->user()->birth)->month == $i ? 'selected' : '' }}>
                                            {{ Carbon::create()->month($i)->getTranslatedMonthName('MMMM') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="w-100">
                                <select class="form-select" id="InputYear" name="birthYear" required autofocus>
                                    <option selected disabled>Год</option>
                                    @for ($i = date('Y'); $i >= date('Y') - 120; $i--)
                                        <option value="{{ $i }}"
                                            {{ Carbon::parse(auth()->user()->birth)->year == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Семейное положение</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="family_status">
                                <option selected value="">Не выбрано</option>
                                @foreach ($familyStatus as $item)
                                    <option value="{{ $item }}"
                                        {{ optional(auth()->user()->info)->family_status == $item ? 'selected' : '' }}>
                                        {{ $item->description(auth()->user()->sex) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Образование</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="education">
                                <option selected value="">Не выбрано</option>
                                @foreach ($education as $item)
                                    <option value="{{ $item }}"
                                        {{ optional(auth()->user()->info)->education == $item ? 'selected' : '' }}>
                                        {{ $item->description() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabelSm" class="col-sm-3 col-form-label">Местоположение</label>
                        <div class="col-sm-9 d-flex justify-content-between gap-2">
                            <div class="w-100">
                                <div id="forSelectRegion1">
                                    <select class="form-select" id="selectRegion1" name="region1">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[0] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ ($location[1]['id'] ?? null) == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-100">
                                <div id="forSelectRegion2">
                                    <select class="form-select" id="selectRegion2" name="region2"
                                        data-parent="{{ $location[1]['id'] ?? null }}">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[1]['areas'] ?? [] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ ($location[2]['id'] ?? null) == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-100">
                                <div id="forSelectRegion3">
                                    <select class="form-select" id="selectRegion3" name="region3"
                                        data-parent="{{ $location[2]['id'] ?? null }}">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[2]['areas'] ?? [] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ ($location[3]['id'] ?? null) == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end pt-4 border-top mb-1">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
