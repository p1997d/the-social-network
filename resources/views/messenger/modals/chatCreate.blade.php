<div class="modal fade" id="chatCreateModal" tabindex="-1" aria-labelledby="chatCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('messages.chat.create') }}">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="chatCreateModalLabel">Создание чата</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @foreach ($friends as $friend)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-check-label stretched-link" for="user{{ $friend->id }}">
                                    <img src="{{ $friend->avatar() }}" class="rounded-circle object-fit-cover"
                                        width="32" height="32" />
                                    {{ $friend->firstname }} {{ $friend->surname }}
                                </label>
                                <input class="form-check-input me-1" type="checkbox" value="{{ $friend->id }}"
                                    id="user{{ $friend->id }}" name="users[]">
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer d-flex justify-content-between align-self-center">
                    <div class="flex-fill">
                        <input class="form-control" type="text" name="title" required placeholder="Введите название чата">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm">Создать чат</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
