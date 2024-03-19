@extends('layouts.index')

@section('card_header_buttons')
    @include('friends.layouts.header')
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card friends-pjax shadow">
                @include('layouts.cardHeader')
                <div class="card-body">
                    @include('friends.layouts.body')
                </div>
            </div>
        </div>
    </div>
@endsection
