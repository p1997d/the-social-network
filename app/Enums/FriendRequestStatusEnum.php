<?php

namespace App\Enums;

enum FriendRequestStatusEnum: int
{
    case SENT_FRIEND_REQUEST = 0;
    case APPROVED_FRIEND_REQUEST = 1;
    case REJECTED_FRIEND_REQUEST = 2;
    case CANCELED_FRIEND_REQUEST = 3;
    case UNFRIEND = 4;

    public function description(): string
    {
        return match ($this) {
            self::SENT_FRIEND_REQUEST => "Отправленный запрос на добавление в друзья",
            self::APPROVED_FRIEND_REQUEST => "Принятый запрос на добавление в друзья",
            self::REJECTED_FRIEND_REQUEST => "Отклоненный запрос на добавление в друзья",
            self::CANCELED_FRIEND_REQUEST => "Отмененный запрос на добавление в друзья",
            self::UNFRIEND => "Удаление из друзей",
        };
    }
}
