<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="position-absolute top-0 end-0 z-3 m-3 d-none d-lg-block">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-dialog modal-fullscreen p-5">
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
                            <div class="carousel-inner h-inherit imageModalCarousel"></div>
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
                            <div
                                class="photoFooter shadow-top py-3 px-4 z-3 d-flex justify-content-end justify-content-lg-between">
                                <div class="photoCounter gap-2 d-none d-lg-flex">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                                <div class="photoButtons">
                                    <button class="btn btn-link p-0 link-secondary photoDeleteButton">Удалить</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 d-lg-flex d-none">
                            <div class="card shadow photoComments w-100 h-100 placeholder-glow">
                                <div class="card-header d-flex align-items-center gap-1">
                                    <div>
                                        <a class="link-body-emphasis photoModalImageLink">
                                            <img class="rounded-circle object-fit-cover placeholder photoModalAvatar"
                                                width="40px" height="40px">
                                        </a>
                                    </div>
                                    <div class="w-100 h-100">
                                        <div>
                                            <a class="link-body-emphasis photoModalLink">
                                                <span class="placeholder col-6"></span>
                                            </a>
                                        </div>
                                        <div class="text-secondary fs-7 photoModalDate">
                                            <span class="placeholder col-4"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column p-0">
                                    <div class="border-bottom p-3 d-flex">
                                        <form action="{{ route('like') }}" method="post" class="setLike">
                                            @csrf
                                            <input type="hidden" name="id" value="">
                                            <input type="hidden" name="type" value="">
                                            <button type="submit" class="btn btn-sm" disabled>
                                                <i class="bi bi-heart-fill"></i>
                                                <span class="countLikes"></span>
                                            </button>
                                        </form>
                                    </div>
                                    <div
                                        class="text-center text-secondary d-flex justify-content-center align-items-center flex-fill p-3 w-100 h-100 photoModalComments">
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
    </div>
</div>
