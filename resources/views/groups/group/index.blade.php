@extends('layouts.index')

@section('content')
    <div class="card mb-3">
        @include('groups.group.layouts.header')
    </div>
    <div class="row h-100">
        <div class="col-lg-8">
            {{-- @include('groups.group.layouts.publications') --}}
            @include('groups.group.layouts.posts')
        </div>
        <div class="col">
            @include('groups.group.layouts.followers')
        </div>
    </div>
@endsection
