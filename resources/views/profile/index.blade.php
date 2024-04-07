@extends('layouts.index')

@section('content')
    <div class="row flex-column flex-lg-row g-3">
        <div class="col-lg-4 d-contents d-lg-block">
            <div class="order-1 mb-3"> @include('profile.layouts.avatar') </div>
            <div class="order-3 mb-3"> @include('profile.layouts.friends') </div>
            <div class="order-5 mb-3"> @include('profile.layouts.groups') </div>
            <div class="order-5 mb-3"> @include('profile.layouts.audios') </div>
        </div>
        <div class="col-lg-8 d-contents d-lg-block">
            <div class="order-2 mb-3"> @include('profile.layouts.info') </div>
            <div class="order-4 mb-3"> @include('profile.layouts.photos') </div>
            <div class="order-5 mb-3"> @include('profile.layouts.blog') </div>
        </div>
    </div>
    @include('profile.modals.updateAvatar')
@endsection
