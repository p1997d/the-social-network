@extends('layouts.index')

@section('title')
    {{ $user_profile->firstname }} {{ $user_profile->surname }}
@endsection

@section('content')
    <div class="col-lg-auto">
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
    </div>
    <div class="col-lg">

        <div class="d-none d-lg-block">
            @include('profile.layouts.info')
        </div>

        @include('profile.layouts.photos')
        @include('profile.layouts.blog')
    </div>

    @include('profile.modals.updateavatar')
@endsection
