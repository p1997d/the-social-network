@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg">
            <div class="card h-100 shadow">
                @include('layouts.cardHeader')
                <div class="card-body container">
                    @yield('content_publications')
                </div>
            </div>
        </div>
        @yield('modal_publications')
    </div>
@endsection
