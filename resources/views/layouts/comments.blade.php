@auth
    <div class="card shadow mb-3" id="comments">
        <div class="card-header">
            <form method="POST" action="{{ route('comment.create') }}" id="sendCommentForm">
                @csrf
                <div class="d-flex gap-2 align-items-center">
                    <div>
                        @include('layouts.avatar', [
                            'model' => auth()->user(),
                            'width' => '32px',
                            'height' => '32px',
                            'class' => 'rounded-circle object-fit-cover',
                            'modal' => false,
                        ])
                    </div>

                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <input type="hidden" name="type" value="{{ $model->getMorphClass() }}">

                    <input type="text" enterkeyhint="send" class="form-control" style="resize:none" id="content"
                        name="content" autocomplete="off" placeholder="Написать комментарий...">
                    <div id="forButton">
                        <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-send"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endauth
<div class="commentsBlock">
    @foreach ($comments as $comment)
        <div class="card shadow mb-3">
            <div class="card-body d-flex gap-2 fs-7">
                @include('layouts.avatar', [
                    'model' => $comment->authorUser,
                    'width' => '40px',
                    'height' => '40px',
                    'class' => 'rounded-circle object-fit-cover',
                    'modal' => false,
                ])
                <div>
                    <div>
                        <a href="{{ route('profile', $comment->authorUser->id) }}"
                            class="profileNameLink">{{ $comment->authorUser->firstname }}
                            {{ $comment->authorUser->surname }}</a>
                    </div>
                    <div class="m-0 p-0 text-break content">
                        {{ $comment->content }}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <span class="text-secondary fs-7 sent-at" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                                data-bs-title="{{ $comment->createdAtIsoFormat }}">
                                {{ $comment->createdAtDiffForHumans }}
                            </span>
                        </div>
                        @auth
                            @if ($comment->author === auth()->user()->id || $model->author === auth()->user()->id)
                                <div>
                                    <form action="{{ route('comment.delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $comment->id }}">
                                        <button type="submit" class="btn btn-link fs-7 p-0">Удалить комментарий</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary getCommentsButton" data-page="2" data-id="{{ $model->id }}"
            data-type="{{ $model->getMorphClass() }}">
            Загрузить ещё...
        </button>
    </div>
</div>
