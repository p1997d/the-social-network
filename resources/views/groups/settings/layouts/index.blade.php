@extends('layouts.index')

@section('content')
    <div class="row h-100">
        <div class="col-lg-8">
            <div class="card h-100 shadow">
                <div class="card-header">
                    <h5>@yield('groupSettingsTitle')</h5>
                </div>
                <div class="card-body h-100">
                    @yield('groupSettingsBody')
                </div>
            </div>
        </div>
        @include('groups.settings.layouts.navigation')
    </div>
@endsection
