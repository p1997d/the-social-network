@extends('publications.index')

@section('card_header_buttons')
    @include('publications.audios.header')
@endsection

@section('content_publications')
    @include('publications.audios.content')
@endsection

@section('modal_publications')
    @include('publications.audios.modals.uploadfile')
@endsection
