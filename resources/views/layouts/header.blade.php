<header>
    <nav class="navbar bg-body-tertiary fixed-top shadow">
        <div class="container">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex flex-column fs-8" href="/">
                    <div>The</div>
                    <div>Social</div>
                    <div>Network</div>
                </a>

                @if (isset($title))
                    <div class="d-lg-none d-flex fs-5 fw-bold me-3" id="header-title">{{ $title }}</div>
                @endif

                @auth
                    <form class="d-lg-flex d-none" role="search" method="GET" action="/search">
                        <div class="input-group">
                            <div class="input-group-text border-end-0 bg-body pe-0"><i class="bi bi-search"></i></div>
                            <input class="form-control me-2 border-start-0 mainSearchForm" type="search" name="query"
                                placeholder="Поиск" aria-label="Search" enterkeyhint="search">
                        </div>
                    </form>

                    @include('layouts.player')

                @endauth
            </div>
            <div class="d-lg-flex d-none">
                @include('layouts.profileDroplist')
            </div>
        </div>
    </nav>
    <nav class="navbar bg-body-tertiary d-lg-none d-block fixed-bottom shadow-top">
        <div class="container">
            <div class="container text-center">
                @auth
                    <div class="row py-1">
                        @foreach ($menu as $item)
                            <div class="col">
                                <a href="{{ $item->link }}" class="btn btn-text border-0 p-0 position-relative">
                                    <div>
                                        <i class="bi {{ $item->icon }}"></i>
                                    </div>

                                    @if ($item->counter)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger {{ $item->name . 'Counter' }}"
                                            style="font-size: 0.6rem">
                                            {{ $item->counter }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                        <div class="col">
                            <button class="btn btn-text border-0 p-0" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#menu" aria-controls="menu">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <div>
                        <a href="{{ route('auth.signin') }}" class="btn btn-secondary my-2">
                            Войти
                        </a>
                        <a href="{{ route('auth.signup') }}" class="btn btn-secondary my-2">
                            Зарегистрироваться
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>
