@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                @include('layouts.cardHeader')
                <div class="card-body">
                    <form method="POST" action="{{ route('info.updateProfile') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Имя</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="colFormLabelSm" name="firstname"
                                    value="{{ auth()->user()->firstname }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Фамилия</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="colFormLabelSm" name="surname"
                                    value="{{ auth()->user()->surname }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Пол</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="sex" id="selectSex">
                                    <option selected disabled>Не выбрано</option>
                                    <option value="male" {{ auth()->user()->sex == 'male' ? 'selected' : '' }}>Мужской
                                    </option>
                                    <option value="female" {{ auth()->user()->sex == 'female' ? 'selected' : '' }}>Женский
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="InputBirth" class="col-sm-3 col-form-label text-secondary">День рождения</label>
                            <div class="col-sm-9 d-flex justify-content-between gap-2" id="InputBirth">
                                <div class="w-100">
                                    <select class="form-select" id="InputDay" name="birthDay" required autofocus>
                                        <option selected disabled>День</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}"
                                                {{ auth()->user()->birthDate()->day == $i ? 'selected' : '' }}>
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
                                                {{ auth()->user()->birthDate()->month == $i ? 'selected' : '' }}>
                                                {{ $months[$i] }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="w-100">
                                    <select class="form-select" id="InputYear" name="birthYear" required autofocus>
                                        <option selected disabled>Год</option>
                                        @for ($i = date('Y'); $i >= date('Y') - 120; $i--)
                                            <option value="{{ $i }}"
                                                {{ auth()->user()->birthDate()->year == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Семейное положение</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="family_status" id="selectFamilyStatus">
                                    <option selected value="">Не выбрано</option>
                                    @foreach ($familyStatus as $item)
                                        <option value="{{ $item }}"
                                            {{ $userinfo->family_status == $item ? 'selected' : '' }}>
                                            {{ $item->description(auth()->user()->sex) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Образование</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="education" id="selectEducation">
                                    <option selected value="">Не выбрано</option>
                                    @foreach ($education as $item)
                                        <option value="{{ $item }}"
                                            {{ $userinfo->education == $item ? 'selected' : '' }}>
                                            {{ $item->description() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label text-secondary">Местоположение</label>
                            <div class="col-sm-9 row pe-0">
                                <div class="col-4 pe-0" id="forSelectRegion1">
                                    <select class="form-select" id="selectRegion1" name="region1">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[0] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ optional(json_decode($userinfo->location))[0] == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 pe-0" id="forSelectRegion2">
                                    <select class="form-select" id="selectRegion2" name="region2">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[1] ?? [] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ optional(json_decode($userinfo->location))[1] == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 pe-0" id="forSelectRegion3">
                                    <select class="form-select" id="selectRegion3" name="region3">
                                        <option selected value="">Не выбрано</option>
                                        @foreach ($location[2] ?? [] as $area)
                                            <option value="{{ $area['id'] }}"
                                                {{ optional(json_decode($userinfo->location))[2] == $area['id'] ? 'selected' : '' }}>
                                                {{ $area['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
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
    </div>
@endsection
