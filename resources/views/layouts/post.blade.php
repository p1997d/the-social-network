@php
    use Carbon\Carbon;
@endphp

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
            <a href="{{ $postHeaderLink }}">
                <p class="m-0">{{ $postHeaderTitle }}</p>
            </a>
            <span class="text-secondary"><small>{{ Carbon::parse($post->created_at)->diffForHumans() }}</small></span>
        </div>

        @if ($post->authorUser->id === optional(auth()->user())->id || $postAdminCondition)
            <div class="flex-fill d-flex justify-content-end">
                <div class="dropdown-center">
                    <a class="link-body-emphasis fw-bold link-underline-opacity-0" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{ route('posts.delete', $post->id) }}" method="post">
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
    {{-- <div class="card-footer">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger active btn-sm"><i class="bi bi-heart-fill"></i>
                <span>15</span></button>
            <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chat-left"></i>
                <span>15</span></button>
            <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-share"></i>
                <span>15</span></button>
        </div>
    </div> --}}
</div>
