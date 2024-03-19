@extends('publications.index')

@section('card_header_buttons')
    @include('publications.videos.header')
@endsection

@section('content_publications')
    @include('publications.videos.content')
@endsection

@section('modal_publications')
    @include('publications.videos.modals.uploadfile')
@endsection
