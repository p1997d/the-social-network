@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            @if (!$section)
                <div class="card shadow mb-3">
                    <div class="card-header d-flex gap-2 align-items-center">
                        <div>
                            @include('layouts.avatar', [
                                'model' => auth()->user(),
                                'width' => '32px',
                                'height' => '32px',
                                'class' => 'rounded-circle object-fit-cover',
                                'modal' => false,
                            ])
                        </div>
                        <div class="flex-fill">
                            @include('layouts.forms.createPost', [
                                'recipientName' => 'user',
                                'recipientValue' => auth()->user()->id,
                                'contentPlaceholder' => 'Что у вас нового?',
                            ])
                        </div>
                    </div>
                </div>
            @endif
            @if ($posts)
                <div class="postsList">
                    @foreach ($posts as $post)
                        @include('layouts.post', $post)
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn btn-secondary getNewsButton" data-page="2">
                        Загрузить ещё...
                    </button>
                </div>
            @else
                <div class="card shadow m-0">
                    <div class="card-body">
                        <p class="text-center">Новостей пока нет</p>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-lg-4">
            <div class="col d-lg-block d-none">
                <div class="card shadow position-sticky shadow" style="top: 5rem">
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('feed') }}" class="list-group-item list-group-item-action">Новости</a>
                            <a href="{{ route('feed', ['section' => 'likes']) }}"
                                class="list-group-item list-group-item-action">Понравилось</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
