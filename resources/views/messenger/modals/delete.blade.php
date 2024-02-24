<div class="modal" tabindex="-1" id="deleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="formDelete" class="messageForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Удалить сообщение</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить сообщение?</p>
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
