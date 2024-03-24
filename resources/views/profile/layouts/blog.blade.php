<div class="d-flex flex-column gap-3">
    @auth
        @if (auth()->user()->id == $user_profile->id)
            <div class="card shadow">
                <div class="card-body">
                    <form class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Что у вас нового?" />
                        <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-send"></i></button>
                    </form>
                </div>
            </div>
        @endif
    @endauth


    <div class="card shadow">
        <div class="card-body d-flex justify-content-between">
            <button class="btn btn-outline-secondary">Все записи</button>
            <button class="btn btn-text"><i class="bi bi-search"></i></button>
        </div>
    </div>

    {{-- <div class="card shadow">
        <div class="card-header d-flex align-items-center gap-2">
            <img src="https://placehold.co/40x40" class="rounded-circle h-100" />
            <div>
                <a href="#" class="">
                    <p class="m-0">Имя Фамилия</p>
                </a>
                <span class="text-secondary"><small>16 июл 2023</small></span>
            </div>
        </div>
        <div class="card-body">
            <div class="content">
                <img src="https://placehold.co/740x500" class="w-100" />
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-danger active"><i class="bi bi-heart-fill"></i>
                    <span>15</span></button>
                <button type="button" class="btn btn-outline-secondary"><i class="bi bi-chat-left"></i>
                    <span>15</span></button>
                <button type="button" class="btn btn-outline-secondary"><i class="bi bi-share"></i>
                    <span>15</span></button>
            </div>
        </div>
    </div> --}}
</div>
