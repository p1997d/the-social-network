<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="position-absolute top-0 end-0 z-3 m-3 d-none d-lg-block">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow">
            <div class="modal-header d-flex d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row m-3">
                    <div class="col-9 d-flex flex-column">
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
                            <div class="d-flex gap-2">
                                <form action="{{ route('like') }}" method="post" class="setLike">
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <input type="hidden" name="type" value="">
                                    <button type="submit" class="btn btn-sm" disabled>
                                        <i class="bi bi-heart-fill"></i>
                                        <span class="countLikes"></span>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary btn-sm shareLink"
                                    data-bs-toggle="modal" data-bs-target="#shareModal" data-bs-id=""
                                    data-bs-type="App\Models\Video">
                                    <i class="bi bi-share"></i>
                                </button>
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
                        <div class="comments commentsBlock mb-3 flex-fill"></div>
                        <div class="border-top p-2">
                            <form method="POST" action="{{ route('comment.create') }}" id="sendCommentForm">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    <div>
                                        @auth
                                            @include('layouts.avatar', [
                                                'model' => auth()->user(),
                                                'width' => '32px',
                                                'height' => '32px',
                                                'class' => 'rounded-circle object-fit-cover',
                                                'modal' => false,
                                            ])
                                        @endauth
                                    </div>

                                    <input type="hidden" name="id" value="">
                                    <input type="hidden" name="type" value="App\Models\Video">

                                    <input type="text" enterkeyhint="send" class="form-control" style="resize:none"
                                        id="content" name="content" autocomplete="off"
                                        placeholder="Написать комментарий...">
                                    <div id="forButton">
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column gap-2" id="modalListVideo"
                        style="overflow-y: scroll; max-height: 800px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
