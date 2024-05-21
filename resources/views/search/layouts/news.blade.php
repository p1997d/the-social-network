@forelse ($items as $post)
    @include('layouts.post', $post)
@empty
    <div class="text-center text-secondary">Ваш запрос не дал результатов</div>
@endforelse
