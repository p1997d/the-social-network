@extends('layouts.index')

@section('card_header_buttons')
    @include('groups.list.layouts.header')
@endsection

@section('content')
    <div class="row h-100">
        <div class="col-lg-8">
            <div class="card h-100 shadow">
                @include('layouts.cardHeader')

                <div class="card-body h-100">
                    @include('groups.list.layouts.list')
                </div>
            </div>
        </div>

        @include('groups.list.layouts.navigation')
    </div>
    @include('groups.list.modals.create')
@endsection
