@if ($hasPermission)
    <div class="modal fade" id="uploadphoto" tabindex="-1" aria-hidden="true">
        <form method="POST" action="{{ route('photos.upload') }}" id="formPhotoUpload" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Загрузка новых фотографий</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            @if (isset($group))
                                <input type="hidden" name="group" value="{{ $group->id }}">
                            @endif
                            <input class="form-control" type="file" id="photosIpnut" name="photos" accept="image/*"
                                required>
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
@endif
