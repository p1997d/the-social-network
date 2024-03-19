@extends('layouts.index')

@section('content')
    <div class="row h-100">
        <div class="col-lg-8">
            @yield('content_message')
        </div>

        @include('messenger.layouts.navigation')
    </div>
@endsection
