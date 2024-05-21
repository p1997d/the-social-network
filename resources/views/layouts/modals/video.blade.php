<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="position-absolute top-0 end-0 z-3 m-3 d-none d-lg-block">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded shadow">
            <div class="modal-header d-flex d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow: clip">
                <div class="row m-3">
                    <div class="col-9">
                        <div class="plyr-w-100 mb-3">
                            <video class="video-player w-100"></video>
                        </div>
                        <div class="mb-2 pb-1 border-bottom">
                            <h5 class="titleModalVideo"></h5>
                            <span class="text-secondary fs-7 viewsModalVideo"></span>
                            <span class="separator text-secondary fs-7">•</span>
                            <span class="text-secondary fs-7 createdAtModalVideo"></span>
                        </div>
                        <div class="interaction mb-3 d-flex justify-content-between">
                            <div class="d-flex">
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
                            <div class="d-flex adminsButtons">
                                <form action="{{ route('videos.delete') }}" method="post" class="formDeleteVideo">
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="comments mb-3"></div>
                    </div>
                    <div class="col-3 d-flex flex-column gap-2" id="modalListVideo"
                        style="overflow-y: scroll; max-height: 800px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
