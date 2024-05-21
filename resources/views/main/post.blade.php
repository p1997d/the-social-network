@extends('layouts.index')

@section('content')
    <div class="row h-100">
        <div class="col-lg-8">
            @include('layouts.post', $post)
            @include('layouts.comments', ['model' => $post])
        </div>

        <div class="col d-lg-block d-none">
            <div class="card shadow position-sticky shadow" style="top: 5rem">
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('posts.index', $post->id) }}"
                            class="list-group-item list-group-item-action">
                            Запись на стене
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
