@extends('layouts.index')

@section('title', 'The Social Network')

@section('main')
    <div class="row h-100">
        <div class="col d-flex align-items-center justify-content-center">
            <div class="card" style="width: 35rem;">
                <div class="card-header">
                    <h4 class="text-decoration-underline m-0">Вход</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('login') }}">
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
                        <div class="mb-3">
                            <label for="InputPassword" class="form-label">{{ __('Пароль') }}</label>
                            <input id="InputPassword" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberCheck"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="rememberCheck">
                                {{ __('Сохранить вход') }}
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" data-bs-custom-class="custom-tooltip"
                                    data-bs-title="<div class='text-start'><b>Сохранить вход</b><div/><div class='text-justify'>Выберите, чтобы сохранить данные аккаунта для быстрого входа на этом устройстве</div>">
                                    <small><i class="bi bi-question-circle-fill"></i></small>
                                </span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100 my-2">Войти</button>
                            <a href="{{route('auth.signup')}}" class="btn btn-secondary w-100 my-2">Зарегистрироваться</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
