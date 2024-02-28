@extends('layouts.index')

@section('title', 'Сообщения')

@section('content')
    <div class="col">
        <div class="card d-flex flex-column messages w-auto h-100 shadow">
            <div class="card-header d-flex justify-content-between">
                @include('messenger.layouts.search')
            </div>
            <div class="bg-body-tertiary px-4 pt-2"><b>Сообщения</b></div>
            <div class="card-body d-flex justify-content-between flex-fill bg-body-tertiary rounded pt-0">
                <div
                    class="list-group justify-content-end w-100 h-100 search-messages-list-group flex-column flex-column-reverse">
                    @include('messenger.layouts.messages')
                </div>
            </div>
        </div>
    </div>
@endsection
