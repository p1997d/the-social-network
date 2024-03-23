<?php

namespace App\Services;

use App\Services\MessagesService;
use App\Services\FriendsService;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public $title, $icon, $link, $counter;

    /**
     * Создает новый экземпляр контроллера.
     *
     * @param string $title
     * @param string $icon
     * @param string $link
     * @param int $counter
     */
    public function __construct($title, $icon, $link, $counter = null)
    {
        $this->title = $title;
        $this->icon = $icon;
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
            new self('Моя страница', 'bi-house-door-fill', Auth::check() ? route('profile', Auth::id()) : route('auth.signin')),
            new self('Новости', 'bi-newspaper', route('feed')),
            new self('Сообщения', 'bi-chat-fill', route('messages'), $data['unreadMessagesCount']),
            new self('Друзья', 'bi-person-fill', route('friends'), $data['incomingCount']),
            new self('Группы', 'bi-people-fill', route('groups')),
            new self('Фотографии', 'bi-camera-fill', route('photos')),
            new self('Аудиозаписи', 'bi-music-note-beamed', route('audios')),
            new self('Видеозаписи', 'bi-film', route('videos')),
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
            new self('Домой', 'bi-house', Auth::check() ? route('profile', Auth::id()) : route('auth.signin')),
            new self('Поиск', 'bi-search', '#'),
            new self('Сообщения', 'bi-chat', route('messages'), $data['unreadMessagesCount']),
            new self('Уведомления', 'bi-bell', '#'),
        ];

        return $menu;
    }
}
