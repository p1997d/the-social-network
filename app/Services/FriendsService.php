<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friends;
use Illuminate\Support\Facades\Auth;
use App\Enums\FriendRequestStatusEnum;

class FriendsService
{
    public $title, $icon, $link, $color;

    /**
     * Создает новый экземпляр контроллера.
     *
     * @param string $title
     * @param string $icon
     * @param string $link
     * @param string $color
     */
    public function __construct($title, $icon, $link, $color)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->color = $color;
    }

    /**
     * Получает список всех друзей
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public static function listFriends($user)
    {
        $friends = Friends::where([['user1', $user->id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->orWhere([['user2', $user->id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$user->id])
            ->unique()
            ->toArray();

        return User::whereIn('id', $userIds);
    }

    /**
     * Получает список друзей онлайн
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public static function listOnlineFriends($user)
    {
        $friends = self::listFriends($user)->get();

        $userIds = $friends->filter(function ($friend) {
            return $friend->online()['status'];
        })->pluck('id');

        return User::whereIn('id', $userIds);
    }

    /**
     * Получает список общих друзей
     *
     * @param \App\Models\User $user
     * @return \App\Models\User|null
     */
    public static function listCommonFriends($user)
    {
        $auth_user_id = Auth::id();

        if ($auth_user_id == $user->id) {
            return null;
        }

        $friends1 = Friends::where([['user1', $user->id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->orWhere([['user2', $user->id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->get();

        $friends2 = Friends::where([['user1', $auth_user_id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->orWhere([['user2', $auth_user_id], ['status', FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST]])
            ->get();

        $userIds1 = $friends1->pluck('user1')
            ->merge($friends1->pluck('user2'))
            ->diff([$user->id])
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $userIds2 = $friends2->pluck('user1')
            ->merge($friends2->pluck('user2'))
            ->diff([$user->id])
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $userIds = array_intersect($userIds1, $userIds2);

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    /**
     * Получает список исходящих запросов дружбы
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public static function listOutgoing()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user1', $auth_user_id], ['status', FriendRequestStatusEnum::SENT_FRIEND_REQUEST]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    /**
     * Получает список входящих запросов дружбы
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public static function listIncoming()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user2', $auth_user_id], ['status', FriendRequestStatusEnum::SENT_FRIEND_REQUEST]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    /**
     * Получает всех друзей пользователей
     *
     * @param \App\Models\User $user
     * @return object|null
     */
    public static function getAllFriends($user)
    {
        if (Auth::guest()) {
            return null;
        }

        $auth_user_id = Auth::id();
        $user_id = $user->id;

        $friend = Friends::where([['user1', $user_id], ['user2', $auth_user_id]])
            ->orWhere([['user1', $auth_user_id], ['user2', $user_id]])->get();

        return $friend;
    }

    /**
     * Получает форму отправки заявок в друзья
     *
     * @param \App\Models\User $user1
     * @return array|null
     */
    public static function getFriendsForms($user1)
    {
        if (Auth::guest()) {
            return null;
        }

        $user2 = User::find(Auth::id());

        $friend = Friends::where([['user1', $user1->id], ['user2', $user2->id]])
            ->orWhere([['user1', $user2->id], ['user2', $user1->id]])->get();

        $forms = [];
        if (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST && $item->user2 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Отменить заявку', 'bi-ban', route('friends.cancelAddFriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST && $item->user1 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Добавить в друзья', 'bi-person-fill-add', route('friends.approveAddFriend', ['user' => $user1->id]), 'btn-primary');
            $forms[] = new self('Отклонить заявку', 'bi-ban', route('friends.rejectAddFriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) {
                return $item->status == FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Убрать из друзей', 'bi-ban', route('friends.unfriend', ['user' => $user1->id]), 'btn-secondary');
        } else {
            $forms[] = new self('Добавить в друзья', 'bi-person-fill-add', route('friends.addFriend', ['user' => $user1->id]), 'btn-primary');
        }

        return $forms;
    }

    /**
     * Получает все списки друзей
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function getAllFriendsLists($user)
    {
        $listFriends = self::listFriends($user);
        $listCommonFriends = self::listCommonFriends($user);
        $listOnline = self::listOnlineFriends($user);
        $listOutgoing = self::listOutgoing();
        $listIncoming = self::listIncoming();

        return array($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming);
    }
}
