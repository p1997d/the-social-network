@extends('layouts.index')

@section('title', 'Видеозаписи')

@section('content')
    <div class="row">
        <div class="col-lg">
            <div class="card h-100 shadow">
                <div class="card-header pb-3">
                    <p class="fs-4 border-bottom d-none d-lg-block pb-3">{{ $title }}</p>
                </div>
                <div class="card-body container"></div>
            </div>
        </div>
    </div>
@endsection
