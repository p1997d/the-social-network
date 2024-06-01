<div class="modal fade" id="shareModal" tabindex="1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="shareModalLabel">Поделиться</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formShare" action="{{ route('share') }}" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="type" value="">

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="radioShare" id="radioShareInPage" value="page">
                        <label class="form-check-label" for="radioShareInPage">
                            На своей стене
                        </label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="radioShare" id="radioShareInGroup" value="group">
                        <label class="form-check-label" for="radioShareInGroup">
                            В группе
                        </label>
                    </div>

                    <div class="mb-2 selectShareInGroupDiv d-none">
                        <select class="form-select" id="selectShareInGroup" name="selectShareInGroup">
                            <option selected disabled value="">В сообществе</option>
                            @foreach (auth()->user()->groupsWhereAdmin() as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="radioShare" id="radioShareInMessage" value="message"
                            checked>
                        <label class="form-check-label" for="radioShareInMessage">
                            В личном сообщении
                        </label>
                    </div>

                    <div class="mb-2 selectShareInMessageDiv">
                        <select class="form-select" id="selectShareInMessage" name="selectShareInMessage" required>
                            <option selected disabled value="">Введите имя получателя или название чата</option>
                            @foreach (auth()->user()->dialogsAndChatsWithMessages() as $chatLog)
                                @if (class_basename($chatLog) == 'Dialog')
                                    <option value="Dialog_{{ $chatLog->id }}">
                                        {{ $chatLog->interlocutor->firstname }}
                                        {{ $chatLog->interlocutor->surname }}
                                    </option>
                                @else
                                    <option value="Chat_{{ $chatLog->id }}">
                                        {{ $chatLog->title }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="content" class="form-label">Ваш комментарий</label>
                        <textarea name="content" id="content" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>
