<div class="modal fade" id="creategroup" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('groups.create') }}" id="formGroupCreate">
        @csrf
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Создание новой группы</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titleInput" class="form-label">Название группы</label>
                        <input class="form-control" type="text" id="titleInput" name="title"
                            placeholder="Введите название" required>
                    </div>
                    <div class="mb-3">
                        <label for="themeInput" class="form-label">Тематика группы</label>
                        <input class="form-control" type="text" id="themeInput" name="theme"
                            placeholder="Введите тему" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button class="btn btn-primary" type="submit">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
