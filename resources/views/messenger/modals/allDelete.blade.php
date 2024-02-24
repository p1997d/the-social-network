<div class="modal" tabindex="-1" id="allDeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{route('messages.alldelete', $recipient->id)}}" class="messageForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Удалить все сообщения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить всю переписку с этим пользователем?</p>
                    <p>Отменить это действие будет невозможно.</p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div id="checkForAll"></div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Удалить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
