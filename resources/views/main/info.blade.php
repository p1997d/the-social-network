@extends('layouts.index')

@section('title', 'The Social Network')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Информация</div>
                <div class="card-body text-center">
                    <p>{{ $info }}</p>
                    <a href="javascript:history.back()" class="btn btn-secondary">Назад</a>
                </div>
            </div>
        </div>
    </div>
@endsection
