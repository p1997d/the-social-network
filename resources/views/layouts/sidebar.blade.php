<div class="list-group position-sticky sidebar w-100">
    @auth
        @foreach ($sidebar as $item)
            <a href="{{ $item->link }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 p-2">
                <div>
                    <span class="text-primary-emphasis me-1">
                        <i class="bi {{ $item->icon }}"></i>
                    </span>
                    {{ $item->title }}
                </div>

                {{-- @if ($item->counter) --}}
                    <span class="badge bg-primary rounded-pill {{ $item->name . 'Counter' }}">{{ $item->counter }}</span>
                {{-- @endif --}}
            </a>
        @endforeach
    @else
        <a href="{{ route('auth.signin') }}" class="btn btn-secondary my-2">
            Войти
        </a>
        <a href="{{ route('auth.signup') }}" class="btn btn-secondary my-2">
            Зарегистрироваться
        </a>
    @endauth
    <hr>
    @include('layouts.footer')
</div>
