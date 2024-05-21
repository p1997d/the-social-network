@extends('layouts.index')

@section('content')
    <div class="card mb-3">
        @include('groups.group.layouts.header')
    </div>
    <div class="row h-100">
        <div class="col-lg-8 order-lg-1 order-2">
            @include('groups.group.layouts.posts')
        </div>
        <div class="col-lg-4 order-lg-2 order-1">
            @include('groups.group.layouts.followers')
            @include('groups.group.layouts.photos')
            @include('groups.group.layouts.audios')
            @include('groups.group.layouts.videos')
        </div>
    </div>
@endsection
