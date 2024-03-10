<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use App\Classes\Sidebar;
use App\Services\PublicationsService;
use App\Services\MessagesService;
use App\Services\FriendsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $request = app(Request::class);

            $unreadMessagesCount = MessagesService::getUnreadMessagesCount();
            $incomingCount = FriendsService::listIncoming()->count();

            $sidebar = [
                new Sidebar('Моя страница', 'bi-house-door-fill', route('index')),
                new Sidebar('Новости', 'bi-newspaper', '#'),
                new Sidebar('Сообщения', 'bi-chat-fill', route('messages'), $unreadMessagesCount),
                new Sidebar('Друзья', 'bi-person-fill', route('friends'), $incomingCount),
                new Sidebar('Группы', 'bi-people-fill', '#'),
                new Sidebar('Фотографии', 'bi-camera-fill', route('photos')),
                new Sidebar('Аудиозаписи', 'bi-music-note-beamed', route('audios')),
                new Sidebar('Видеозаписи', 'bi-film', route('videos')),
            ];

            $data = compact('unreadMessagesCount', 'incomingCount', 'sidebar');

            $queryContent = $request->query('content');
            if ($queryContent) {
                $contentArray = explode('_', $queryContent);
                $user = User::find($contentArray[3]);

                $typeContent = $contentArray[2];

                $to = $request->query('to');
                $chat = $request->query('chat');

                $content = PublicationsService::getPhotos($user, $typeContent, $to, $chat);
                $activeContent = $contentArray[1];

                $data = array_merge($data, compact('content', 'activeContent', 'typeContent'));
            }

            $view->with($data);
        });
    }
}
