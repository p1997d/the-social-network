<div class="modal fade" id="uploadaudio" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('audios.upload') }}" id="formAudioUpload" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Загрузка новых аудиозаписей</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="audiosInput" class="form-label">Загрузите файл</label>
                        <input class="form-control" type="file" id="audiosInput" name="audios" accept="audio/*"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="artistInput" class="form-label">Введите исполнителя</label>
                        <input class="form-control" type="text" id="artistInput" name="artist"
                            placeholder="Rick Astley" required>
                    </div>
                    <div class="mb-3">
                        <label for="titleInput" class="form-label">Введите название</label>
                        <input class="form-control" type="text" id="titleInput" name="title"
                            placeholder="Never Gonna Give You Up" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div style="font-size: 0.7rem" class="fst-italic">
                        <span>Ограничения</span>
                        <ul>
                            <li>Аудиофайл не должен превышать 200 МБ и должен быть в формате МРЗ.</li>
                            <li>Аудиофайл не должен нарушать авторские и смежные права.</li>
                        </ul>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button class="btn btn-primary" type="submit">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
