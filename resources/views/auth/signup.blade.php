@extends('layouts.index')

@section('title', 'The Social Network')

@section('main')
    <div class="row h-100">
        <div class="col d-flex align-items-center justify-content-center">
            <div class="card" style="width: 50rem;">
                <div class="card-header">
                    <h4 class="m-0">Регистрация</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="InputEmail" class="form-label">{{ __('Email') }}</label>
                            <input id="InputEmail" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <div class="w-100">
                                <label for="InputFirstname" class="form-label">{{ __('Имя') }}</label>
                                <input id="InputFirstname" type="text"
                                    class="form-control @error('firstname') is-invalid @enderror" name="firstname"
                                    value="{{ old('firstname') }}" required autocomplete="off" autofocus>

                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="w-100">
                                <label for="InputSurname" class="form-label">{{ __('Фамилия') }}</label>
                                <input id="InputSurname" type="text"
                                    class="form-control @error('surname') is-invalid @enderror" name="surname"
                                    value="{{ old('surname') }}" required autocomplete="off" autofocus>

                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <div class="w-100">
                                <label class="form-label" for="InputSex">{{ __('Пол') }}</label>
                                <div class="btn-group form-control p-0" role="group" id="InputSex">
                                    <input type="radio" class="btn-check" name="sex" value="male" id="male"
                                        required checked>
                                    <label class="btn btn-outline-secondary" for="male">Мужской</label>

                                    <input type="radio" class="btn-check" name="sex" value="female" id="female"
                                        required>
                                    <label class="btn btn-outline-secondary" for="female">Женский</label>
                                </div>

                                @error('sex')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="w-100">
                                <label for="InputBirth" class="form-label">{{ __('День рождения') }}</label>
                                <div class="d-flex justify-content-between gap-2" id="InputBirth">
                                    <div class="w-100">
                                        <select class="form-select @error('birthDay') is-invalid @enderror" id="InputDay"
                                            name="birthDay" required autofocus>
                                            <option selected disabled>День</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('birthDay') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>

                                        @error('birthDay')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="w-100">
                                        <select class="form-select @error('birthMonth') is-invalid @enderror"
                                            id="InputMonth" name="birthMonth" required autofocus>
                                            <option selected disabled>Месяц</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('birthMonth') == $i ? 'selected' : '' }}>
                                                    {{ $months[$i] }}
                                                </option>
                                            @endfor
                                        </select>

                                        @error('birthMonth')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="w-100">
                                        <select class="form-select @error('birthYear') is-invalid @enderror" id="InputYear"
                                            name="birthYear" required autofocus>
                                            <option selected disabled>Год</option>
                                            @for ($i = date('Y'); $i >= date('Y') - 120; $i--)
                                                <option value="{{ $i }}"
                                                    {{ old('birthYear') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>

                                        @error('birthYear')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <div class="w-100">
                                <label for="InputPassword" class="form-label">{{ __('Пароль') }}</label>
                                <input id="InputPassword" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="w-100">
                                <label for="InputConfirmPassword" class="form-label">{{ __('Повторите пароль') }}</label>
                                <input id="InputConfirmPassword" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 my-2">{{ __('Зарегистрироваться') }}</button>
                        <a href="{{ route('auth.signin') }}"
                            class="btn btn-secondary w-100 my-2">{{ __('У меня уже есть аккаунт') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
