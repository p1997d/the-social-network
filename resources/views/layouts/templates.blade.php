<template id="video-template">
    <button class="btn btn-text m-0 p-0 videoCard openVideoModal w-100">
        <div class="card shadow">
            <div class="video-thumbnail position-relative">
                <img src="" class="card-img-top videoThumbnail object-fit-cover" alt="preview"
                    style="width: 100%; height: 150px">
                <div class="position-absolute bottom-0 end-0">
                    <span class="badge text-bg-dark bg-opacity-50 m-1 videoDuration"></span>
                </div>
            </div>
            <div class="card-body text-start">
                <h5 class="card-title videoTitle"></h5>
                <span class="card-text text-secondary fs-7 videoViews"></span>
                <span class="separator text-secondary fs-7">•</span>
                <span class="card-text text-secondary fs-7 videoDate"></span>
            </div>
        </div>
    </button>
</template>

<template id="message-template">
    <div class="list-group-item list-group-item-action gap-2 message justify-content-center rounded border-0 rounded-0">
        <div>
            <a href="" class="profileImageLink">
                <img src="" width="36" height="36" class="rounded-circle object-fit-cover">
            </a>
        </div>
        <div class="w-100">
            <div class="d-flex justify-content-between w-100">
                <div>
                    <a href="" class="profileNameLink"></a>
                    <span class="text-secondary fs-7 sent-at"></span>
                </div>
                <div class="message-buttons">
                    <button class="btn btn-text text-emphasis fs-7 px-1 py-0 editButton" data-bs-messageid="">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-text text-emphasis fs-7 px-1 py-0 deleteModal" data-bs-toggle="modal"
                        data-bs-target="#deleteModal" data-bs-messageid="" data-bs-mymessage="">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="m-0 text-break content">
                <div class="container attachments"></div>
            </div>
        </div>
    </div>
</template>

<template id="filebadge-template">
    <div class="badge text-bg-secondary mt-2 justify-content-between align-items-center d-flex fileBadge"
        style="max-width: 50%">
        <div class="file-name text-break text-truncate"></div>
        <button type="button" class="btn-close" aria-label="Close"></button>
    </div>
</template>

<template id="generaltoast-template">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</template>

<template id="toast-template">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
        <div class="toast-header">
            <strong class="title me-auto"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <a href="#"
                class="link link-body-emphasis link-underline-opacity-0 link-underline-opacity-0-hover link-opacity-100-hover d-flex gap-2 align-items-baseline">
                <div class="my-auto">
                    <img src="" width="36" height="36" class="image rounded-circle object-fit-cover">
                </div>
                <div>
                    <p class="subtitle m-0 fs-5"></p>
                    <p class="description m-0 text-short"></p>
                </div>
            </a>
        </div>
    </div>
</template>

<template id="post-template">
    <div class="card shadow mb-3">
        <div class="card-header d-flex align-items-center gap-2">
            <a href="" class="postImageLink">
                <img class="rounded-circle object-fit-cover postAvatar" width="40px" height="40px">
            </a>
            <div>
                <p class="m-0">
                    <a href="" class="postLink"></a>
                </p>
                <span class="text-secondary fs-7 postDate"></span>
            </div>

            <div class="flex-fill d-flex justify-content-end postDropdown">
                <div class="dropdown-center">
                    <a class="link-body-emphasis fw-bold link-underline-opacity-0" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="" method="post" class="postDelete">
                                @csrf
                                <button class="dropdown-item" type="submit">Удалить запись</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="content postContent"></div>
        </div>
        <div class="card-footer">
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
                {{-- <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chat-left"></i><span>15</span></button> --}}
                {{-- <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-share"></i><span>15</span></button> --}}
            </div>
        </div>
    </div>
</template>

<template id="audio-template">
    <li
        class="list-group-item d-flex justify-content-between align-items-start align-items-lg-center mt-2 flex-column flex-lg-row w-100 gap-2">
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-outline-primary btn-sm playAudioButton" data-id=""
                data-playlist="">
                <i class="bi bi-play"></i>
            </button>
            <a href="" class="btn btn-outline-primary btn-sm audioDownload">
                <i class="bi bi-download"></i>
            </a>
            <button class="btn btn-outline-secondary btn-sm deleteAudioButton" data-audio="">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="flex-grow-1 audioTitle"></div>
        <div class="text-secondary audioDuration"></div>
    </li>
</template>

<template id="photo-template">
    <div class="carousel-item h-inherit" data-photo="" data-user="">
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <img src="" class="d-block mw-100 mh-100 mx-auto rounded displayedImage">
        </div>
    </div>
</template>

<template id="friend-template">
    <form class="" method="POST" action="">
        @csrf
        <button type="submit" class="button">
            <i class="icon"></i>
            <span class="titleFriend"></span>
        </button>
    </form>
</template>
