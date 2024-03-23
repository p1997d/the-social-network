@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3 shadow">
                <div class="card-body">
                    @include('profile.layouts.avatar')
                    @include('profile.layouts.buttons')
                </div>
            </div>

            <div class="d-lg-none d-block">
                @include('profile.layouts.info')
            </div>

            @include('profile.layouts.friends')
            @include('profile.layouts.groups')
            @include('profile.layouts.audios')
        </div>
        <div class="col-lg-8">

            <div class="d-none d-lg-block">
                @include('profile.layouts.info')
            </div>

            @include('profile.layouts.photos')
            @include('profile.layouts.blog')
        </div>

        @include('profile.modals.updateAvatar')
    </div>
@endsection
