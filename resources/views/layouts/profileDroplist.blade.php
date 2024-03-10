@auth
    <div class="dropdown">
        <button class="btn btn-text dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @include('layouts.avatar', [
                'model' => auth()->user(),
                'width' => '32px',
                'height' => '32px',
                'class' => 'rounded-circle object-fit-cover',
                'modal' => false
            ])
            {{ auth()->user()->firstname }}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Настройки</a></li>
            <li>
                <button class="dropdown-item btnSwitch"><i class="bi bi-palette"></i>
                    Тема: <span class="text-primary-emphasis themeText"></span>
                </button>
            </li>
            <li>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right"></i>
                        Выйти
                    </button>
                </form>
            </li>
        </ul>
    </div>
@endauth
