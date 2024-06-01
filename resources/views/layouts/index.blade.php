<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    @section('css')
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

        <!-- Bootstrap icons -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.min.css') }}">

        <!-- Plyr -->
        <link rel="stylesheet" href="{{ asset('plugins/plyr/plyr.css') }}" />

        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap-5-theme.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrapAddons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    @show

    @section('js')
        <!-- jQuery -->
        <script src="{{ asset('plugins/jquery/jquery-3.7.1.min.js') }}"></script>

        <!-- Bootstrap -->
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- jQuery Cookie -->
        <script src="{{ asset('plugins/jquery/jquery.cookie.min.js') }}"></script>

        <!-- jQuery PJAX -->
        <script src="{{ asset('plugins/jquery/jquery.pjax.min.js') }}"></script>

        <!-- Plyr -->
        <script src="{{ asset('plugins/plyr/plyr.js') }}"></script>

        <!-- isInViewport -->
        <script src="{{ asset('plugins/isInViewport/isInViewport.min.js') }}"></script>

        <!-- Select2 -->
        <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/ru.js') }}"></script>

        <script>
            const userId = {{ auth()->check() ? auth()->user()->id : 'null' }};
        </script>

        <script src="{{ asset('js/theme.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/messenger.js') }}"></script>
        <script src="{{ asset('js/friends.js') }}"></script>
        <script src="{{ asset('js/photos.js') }}"></script>
        <script src="{{ asset('js/player.js') }}"></script>
        <script src="{{ asset('js/video.js') }}"></script>
        <script src="{{ asset('js/posts.js') }}"></script>
        <script src="{{ asset('js/editProfile.js') }}"></script>
        <script src="{{ asset('js/interaction.js') }}"></script>

        @vite('resources/js/echo.js')
    @show
</head>

<body class="d-flex flex-column">
    <main class="flex-grow-1 mb-3 container pb-5">
        @include('layouts.header')

        @section('main')
            <div class="row gx-3 h-100 my-lg-0 mt-3 mb-5">
                <div class="col-lg-2 p-0 d-none d-lg-block" id="sidebar">
                    @include('layouts.sidebar')
                </div>
                <div class="col-lg-10" id="pjax-container">
                    @yield('content')
                </div>
            </div>
        @show

        @include('layouts.modals.error')
        @include('layouts.modals.image')
        @include('layouts.modals.video')
        @include('layouts.modals.share')
        @include('layouts.offcanvasMenu')
        @include('layouts.toasts')
        @include('layouts.templates')

        <div class="toast-container position-fixed p-3" style="bottom: 5%; left: 0"></div>
    </main>
    @yield('footer')
</body>

</html>
