@extends('messenger.layouts.index')

@section('content_message')
    <div class="d-flex flex-column messages w-auto h-100">
        @include('messenger.layouts.header')
        <div class="d-flex justify-content-between flex-fill messages-body bg-body-tertiary rounded">
            <div class="list-group justify-content-start w-100 h-100 messages-list-group flex-column-reverse">
                @include('messenger.layouts.messages')
            </div>
        </div>
        @include('messenger.layouts.footer')

        @include('messenger.modals.delete')
        @include('messenger.modals.allDelete')
        @include('messenger.modals.error')
    </div>
@endsection
