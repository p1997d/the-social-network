@extends('messenger.layouts.index')

@section('content_message')
    <div class="card h-100 shadow">
        <div class="card-header d-flex justify-content-between">
            @include('messenger.layouts.search')
            <div>
                <button class="btn btn-text" data-bs-toggle="modal" data-bs-target="#chatCreateModal">
                    <i class="bi bi-pencil-square"></i>
                </button>
            </div>
        </div>

        <div class="card-body h-100 messages-pjax">
            @include('messenger.layouts.list')
        </div>
    </div>
    @include('messenger.modals.chatCreate')
@endsection
