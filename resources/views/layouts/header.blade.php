<header class="header-pjax">
    <nav class="navbar bg-body-tertiary fixed-top shadow">
        <div class="container d-none d-lg-flex">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex flex-column fs-8" href="/">
                    <div>The</div>
                    <div>Social</div>
                    <div>Network</div>
                </a>
                @auth
                    <form class="d-flex" role="search" method="GET" action="/search">
                        <div class="input-group">
                            <div class="input-group-text border-end-0 bg-body pe-0"><i class="bi bi-search"></i></div>
                            <input class="form-control me-2 border-start-0 mainSearchForm" type="search" name="query"
                                placeholder="Поиск" aria-label="Search" enterkeyhint="search">
                        </div>
                    </form>
                @endauth
            </div>
            @include('layouts.profileDroplist')
        </div>
        @if (isset($title))
            <div class="container d-lg-none d-flex py-1">
                <div class="d-flex align-items-center fs-5 fw-bold">
                    {{ $title }}
                </div>
            </div>
        @endif
    </nav>
    <nav class="navbar bg-body-tertiary d-lg-none d-block fixed-bottom shadow-top">
        <div class="container">
            <div class="container text-center">
                <div class="row py-1">
                    <div class="col">
                        <a href="{{ route('index') }}" class="btn btn-text border-0 p-0"><i class="bi bi-house"></i></a>
                    </div>
                    <div class="col">
                        <a href="#" class="btn btn-text @guest disabled @endguest border-0 p-0"><i
                                class="bi bi-search"></i></a>
                    </div>
                    <div class="col">
                        <a href="{{ route('messages') }}"
                            class="btn btn-text @guest disabled @endguest border-0 position-relative p-0">
                            <i class="bi bi-chat"></i>
                            @if ($unreadMessagesCount)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger"
                                    style="font-size: 0.6rem">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="btn btn-text @guest disabled @endguest border-0 p-0"><i
                                class="bi bi-bell"></i></a>
                    </div>
                    <div class="col">
                        <button class="btn btn-text border-0 p-0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#menu" aria-controls="menu">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
