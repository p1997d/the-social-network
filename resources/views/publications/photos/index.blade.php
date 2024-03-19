@extends('publications.index')

@section('card_header_buttons')
    @include('publications.photos.header')
@endsection

@section('content_publications')
    @include('publications.photos.content')
@endsection

@section('modal_publications')
    @include('publications.photos.modals.uploadfile')
@endsection
