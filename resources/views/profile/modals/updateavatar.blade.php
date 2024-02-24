<div class="modal fade" id="updateavatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{ route('info.updateavatar') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Загрузка новой фотографии</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="avatar" class="form-label text-center mb-3">
                            Друзьям будет проще узнать вас, если вы загрузите
                            свою настоящую фотографию.
                            Вы можете загрузить изображение в формате JPG, GIF или PNG.
                        </label>
                        <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div style="font-size: 0.7rem" class="fst-italic">
                        Если у вас возникают проблемы с загрузкой, попробуйте выбрать фотографию меньшего размера.
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
