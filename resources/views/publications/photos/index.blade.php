@extends('layouts.index')

@section('title', $title)

@php
    use Carbon\Carbon;
    use Carbon\CarbonInterface;
    Carbon::setLocale('ru');
@endphp

@section('content')
    <div class="row">
        <div class="col-lg">
            <div class="card h-100 shadow">
                <div class="card-header pb-3">
                    <p class="fs-4 border-bottom d-none d-lg-block pb-3">{{ $title }}</p>
                    <div class="d-flex justify-content-between pt-2">
                        <div>
                            <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id]) }}"
                                class="btn btn-emphasis @if (!$type) active @endif">Все</a>
                            <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'type' => 'profile']) }}"
                                class="btn btn-emphasis @if ($type == 'profile') active @endif">Фото профиля</a>
                            <a href="{{ route(\Route::currentRouteName(), ['id' => $user->id, 'type' => 'uploaded']) }}"
                                class="btn btn-emphasis @if ($type == 'uploaded') active @endif">Загруженные
                                фото</a>
                            {{-- <a class="btn btn-emphasis">Фото на стене</a> --}}
                        </div>
                        <div>
                            @if (auth()->user()->id == $user->id)
                                <button class="btn btn-emphasis-invert" data-bs-toggle="modal"
                                    data-bs-target="#uploadphoto"><i class="bi bi-image"></i> Загрузить фото</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body container">
                    <div class="d-flex justify-content-start flex-wrap gap-2">
                        @forelse ($photos as $i => $photo)
                            @if (
                                $loop->first ||
                                    (isset($photos[$i + 1]) &&
                                        !Carbon::parse($photos[$i + 1]->created_at)->isSameDay(Carbon::parse($photo->created_at))))
                                <div class="w-100 pt-3 pb-1 text-secondary">
                                    <p class="m-0 p-0">{{ $photo->date() }}</p>
                                </div>
                            @endif

                            <div class="openImageModal" data-user="{{ $photo->author }}" data-photo="{{ $photo->id }}"
                                data-type="{{ $type ?: 'all' }}" tabindex="0">
                                <img src="{{ asset("storage/files/$photo->path") }}" class="photos rounded" />
                            </div>
                        @empty
                            <div class="w-100 text-center">
                                @if (auth()->user()->id == $user->id)
                                    <p>Вы ещё не загружали фото</p>
                                @else
                                    <p>{{ $user->firstname }} ещё не
                                        добавил{{ $user->sex == 'female' ? 'а' : '' }}
                                        фотографии
                                    </p>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @include('publications.photos.modals.uploadfile')
    </div>
@endsection
