@if ($hasPermission)
    <div class="modal fade" id="uploadvideo" tabindex="-1" aria-hidden="true">
        <form method="POST" action="{{ route('videos.upload') }}" id="formVideoUpload" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Загрузка новых видеозаписей</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="videosInput" class="form-label">Загрузите файл</label>
                            <input class="form-control" type="file" id="videosInput" name="videos" accept="video/*"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="titleInput" class="form-label">Введите название</label>
                            <input class="form-control" type="text" id="titleInput" name="title" placeholder=""
                                required>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div style="font-size: 0.7rem" class="fst-italic">
                            <span>Ограничения</span>
                            <ul>
                                <li>Видеофайл не должен превышать 200 МБ и должен быть в формате МР4.</li>
                                <li>Видеофайл не должен нарушать авторские и смежные права.</li>
                            </ul>
                        </div>
                        <div>
                            @if (isset($group))
                                <input type="hidden" name="group" value="{{ $group->id }}">
                            @endif
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button class="btn btn-primary" type="submit">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif
