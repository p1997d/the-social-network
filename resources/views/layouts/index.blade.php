<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @section('css')
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Bootstrap icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Plyr -->
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrapAddons.css') }}" rel="stylesheet">
    @show

    @section('js')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>

        <!-- jQuery Cookie -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"
            integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- jQuery PJAX -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"
            integrity="sha512-7G7ueVi8m7Ldo2APeWMCoGjs4EjXDhJ20DrPglDQqy8fnxsFQZeJNtuQlTT0xoBQJzWRFp4+ikyMdzDOcW36kQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Plyr -->
        <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

        <script>
            const userId = {{ auth()->check() ? auth()->user()->id : null }};
        </script>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/friends.js') }}"></script>
        <script src="{{ asset('js/theme.js') }}"></script>


        @vite('resources/js/echo.js')
    @show
</head>

<body class="d-flex flex-column">
    @include('layouts.header')

    <main class="flex-grow-1 mb-3 container pb-5">
        @section('main')
            <div class="row gx-3 h-100">
                <div class="col-auto p-0 d-none d-lg-block" id="sidebar">
                    @include('layouts.sidebar')
                </div>
                @yield('content')
            </div>
        @show

        @include('layouts.modals.image')
        @include('layouts.offcanvasMenu')
        @include('layouts.notifications')
    </main>

    @yield('footer')
</body>

</html>