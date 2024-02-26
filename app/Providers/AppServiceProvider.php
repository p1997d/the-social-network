<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Sidebar;
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
            $unreadMessagesCount = MessagesService::getUnreadMessagesCount();
            $incomingCount = FriendsService::listIncoming()->count();

            $sidebar = [
                new Sidebar('Моя страница', 'bi-house-door-fill', route('index')),
                new Sidebar('Новости', 'bi-newspaper', '#'),
                new Sidebar('Сообщения', 'bi-chat-fill', route('messages'), $unreadMessagesCount),
                new Sidebar('Друзья', 'bi-person-fill', route('friends'), $incomingCount),
                new Sidebar('Группы', 'bi-people-fill', '#'),
                new Sidebar('Фотографии', 'bi-camera-fill', '#'),
                new Sidebar('Аудиозаписи', 'bi-music-note-beamed', '#'),
                new Sidebar('Видеозаписи', 'bi-film', '#'),
            ];

            $view->with(compact('unreadMessagesCount', 'incomingCount', 'sidebar'));
        });
    }
}
