<div
    class=" @if ($buttons) d-flex flex-column gap-3 friendFormsButtons @else d-flex align-items-center gap-2 friendFormsLinks @endif">
    @foreach ($friendForm as $form)
        @if (!$buttons)
            <span class="separator">·</span>
        @endif
        <form class="@if ($buttons) w-100 @endif formFriends"
            method="POST"action="{{ $form->link }}">
            @csrf
            <button type="submit"
                class="btn @if ($buttons) {{ $form->color }} w-100 @else btn-link p-0 fs-7 @endif">

                @if ($buttons)
                    <i class="bi {{ $form->icon }}"></i>
                @endif
                <span>{{ $form->title }}</span>
            </button>
        </form>
    @endforeach
</div>
