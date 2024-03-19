@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                @include('layouts.cardHeader')
                <div class="card-body text-center">
                    <p>{{ $info }}</p>
                    <a href="javascript:history.back()" class="btn btn-secondary">Назад</a>
                </div>
            </div>
        </div>
    </div>
@endsection
