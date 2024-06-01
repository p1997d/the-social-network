<div class="card shadow mb-3">
    <div class="card-header d-flex align-items-center gap-2">
        <a href="{{ $postHeaderLink }}">
            @include('layouts.avatar', [
                'model' => $postHeaderAvatar,
                'width' => '40px',
                'height' => '40px',
                'class' => 'rounded-circle object-fit-cover',
                'modal' => false,
            ])
        </a>
        <div>
            <p class="m-0">
                <a href="{{ $postHeaderLink }}">{{ $postHeaderTitle }}</a>
            </p>
            <a href="{{ route('posts.index', $post->id) }}"
                class="postLink link-secondary link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                <span class="text-secondary fs-7">{{ $post->createdAtDiffForHumans() }}</span>
            </a>
        </div>

        @if ($post->authorUser->id === optional(auth()->user())->id || $postAdminCondition)
            <div class="flex-fill d-flex justify-content-end">
                <div class="dropdown-center">
                    <a class="link-body-emphasis fw-bold link-underline-opacity-0" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{ route('posts.delete', $post->id) }}" method="post" class="postDelete">
                                @csrf
                                <button class="dropdown-item" type="submit">Удалить запись</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="content">
            {{ Crypt::decrypt($post->content) }}
            @include('layouts.attachments', [
                'model' => $post,
                'group' => 'post' . $post->id,
            ])
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex gap-2">
            <form action="{{ route('like') }}" method="post" class="setLike"
                data-like="{{ class_basename($post) }}{{ $post->id }}">
                @csrf
                <input type="hidden" name="id" value="{{ $post->id }}">
                <input type="hidden" name="type" value="{{ $post->getMorphClass() }}">
                <button type="submit"
                    class="btn btn-sm @if ($post->myLike !== null) btn-outline-danger active
                    @else btn-outline-secondary @endif">
                    <i class="bi bi-heart-fill"></i>
                    <span class="countLikes">{{ $post->likes->count() }}</span>
                </button>
            </form>
            <a href="{{ route('posts.index', $post->id) }}#comments" type="button"
                class="btn btn-outline-secondary btn-sm commentsLink">
                <i class="bi bi-chat-left"></i>
                <span>{{ $post->comments->count() }}</span>
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm shareLink" data-bs-toggle="modal"
                data-bs-target="#shareModal" data-bs-id="{{ $post->id }}"
                data-bs-type="{{ $post->getMorphClass() }}">
                <i class="bi bi-share"></i>
            </button>
        </div>
    </div>
</div>
