<?php

namespace App\Services;

use App\Services\MessagesService;
use App\Services\FriendsService;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public $title, $icon, $name, $link, $counter;

    /**
     * Создает новый экземпляр контроллера.
     *
     * @param string $title
     * @param string $icon
     * @param string $name
     * @param string $link
     * @param int $counter
     */
    public function __construct($title, $icon, $name, $link, $counter = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->name = $name;
        $this->link = $link;
        $this->counter = $counter;
    }

    /**
     * Получает счетчики непрочитанных сообщений и входящих заявок в друзья
     *
     * @return array
     */
    public static function getCounters()
    {
        $unreadMessagesCount = MessagesService::getUnreadMessagesCount();
        $incomingCount = FriendsService::listIncoming()->count();

        $unreadMessagesCount = $unreadMessagesCount > 0 ? $unreadMessagesCount : '';
        $incomingCount = $incomingCount > 0 ? $incomingCount : '';

        return compact('unreadMessagesCount', 'incomingCount');
    }

    /**
     * Получает список кнопок боковой панели
     *
     * @return array
     */
    public static function getSidebar()
    {
        $data = self::getCounters();

        $sidebar = [
            new self('Моя страница', 'bi-house-door-fill', 'profile', Auth::check() ? route('profile', Auth::id()) : route('auth.signin')),
            new self('Новости', 'bi-newspaper', 'feed', route('feed')),
            new self('Сообщения', 'bi-chat-fill', 'messages', route('messages'), $data['unreadMessagesCount']),
            new self('Друзья', 'bi-person-fill', 'friends', route('friends'), $data['incomingCount']),
            new self('Группы', 'bi-people-fill', 'groups', route('groups.list')),
            new self('Фотографии', 'bi-camera-fill', 'photos', route('photos')),
            new self('Аудиозаписи', 'bi-music-note-beamed', 'audios', route('audios')),
            new self('Видеозаписи', 'bi-film', 'videos', route('videos')),
        ];

        return $sidebar;
    }

    /**
     * Получает список кнопок панели навигации
     *
     * @return array
     */
    public static function getNavbar()
    {
        $data = self::getCounters();

        $menu = [
            new self('Домой', 'bi-house', 'profile', Auth::check() ? route('profile', Auth::id()) : route('auth.signin')),
            new self('Поиск', 'bi-search', 'search', route('search.all')),
            new self('Сообщения', 'bi-chat', 'messages', route('messages'), $data['unreadMessagesCount']),
            // new self('Уведомления', 'bi-bell', 'notifications', '#'),
        ];

        return $menu;
    }
}
