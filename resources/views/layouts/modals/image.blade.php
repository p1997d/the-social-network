@php
    use Carbon\Carbon;
    Carbon::setLocale('ru');
@endphp

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="position-absolute top-0 end-0 z-3 m-3 d-none d-lg-block">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded shadow">
            <div class="modal-header d-flex d-lg-none">
                <div class="photoCounter gap-2 d-flex">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow: clip">
                <div class="photoContainer h-100">
                    <div class="photoImage h-inherit row g-0">
                        <div id="carouselIndicators" class="carousel slide h-inherit d-flex flex-column col-12 col-lg-8">
                            <div class="carousel-inner h-inherit">
                                @if (isset($content) && $typeContent == 'photo')
                                    @foreach ($content as $item)
                                        <div class="carousel-item h-inherit
                                                    {{ $item->id == $activeContent ? 'active' : '' }}"
                                            data-photo="{{ $item->id }}" data-user="{{ $item->author }}"
                                            data-group="{{ $groupContent }}">
                                            <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                                                <img src="storage/files/{{ $item->path }}"
                                                    class="d-block mw-100 mh-100 mx-auto rounded displayedImage">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="photoFooter shadow-top py-3 px-4 z-3 d-flex justify-content-end justify-content-lg-between">
                                <div class="photoCounter gap-2 d-none d-lg-flex">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="photoButtons">
                                    <button class="btn btn-link p-0 link-secondary photoDeleteButton">Удалить</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 d-lg-flex d-none">
                            <div class="card shadow photoComments w-100 h-100">
                                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                                    <div class="spinner-border" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
