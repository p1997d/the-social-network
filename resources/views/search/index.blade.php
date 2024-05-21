@extends('layouts.index')

@section('card_header_buttons')
    @include('search.layouts.header')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                @include('layouts.cardHeader')
                <div class="card-body">
                    @forelse ($results as $result)
                        @if ((isset(optional($result)->items) && $result->items->isNotEmpty()) || $type !== 'all')
                            <div class="mb-3">
                                @include('search.layouts.title', [
                                    'title' => $result->title,
                                    'link' => $result->link,
                                    'linkTitle' => $result->linkTitle,
                                ])
                                @include($result->template, [
                                    'items' => $result->items,
                                ])
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-secondary">Ваш запрос не дал результатов</div>
                    @endforelse
                </div>
            </div>
        </div>
        @include('search.layouts.navigation')
    </div>
@endsection
