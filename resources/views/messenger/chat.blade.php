@extends('layouts.index')

@section('title', 'Сообщения')

@section('js')
    @parent
    <script src="{{ asset('js/messanger.js') }}"></script>

    <!-- isInViewport -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/is-in-viewport/3.0.4/isInViewport.min.js"
        integrity="sha512-eAT5Hvi9/Yx33YvSUPOpAYEA3HnUt5oKCSyPGRQwNgqD7K/90JRpFnkaL1M6ROZtLkckQKJ4WYh9cS7Urr4YjA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <div class="col-lg-7">
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
    </div>
    @include('messenger.layouts.navigation')
@endsection
