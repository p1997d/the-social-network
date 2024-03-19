<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friends;
use Illuminate\Support\Facades\Auth;

class FriendsService
{
    public $title, $icon, $link, $color;

    public function __construct($title, $icon, $link, $color)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->color = $color;
    }

    public static function listFriends($user)
    {
        $friends = Friends::where([['user1', $user->id], ['status', 1]])
            ->orWhere([['user2', $user->id], ['status', 1]])
            ->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$user->id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public static function listOnlineFriends($user)
    {
        $friends = self::listFriends($user)->get();

        $userIds = $friends->filter(function ($friend) {
            return $friend->online()['status'];
        })->pluck('id');

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public static function listCommonFriends($user)
    {
        $auth_user_id = Auth::id();

        if ($auth_user_id == $user->id) {
            return null;
        }

        $friends1 = Friends::where([['user1', $user->id], ['status', 1]])
            ->orWhere([['user2', $user->id], ['status', 1]])
            ->get();

        $friends2 = Friends::where([['user1', $auth_user_id], ['status', 1]])
            ->orWhere([['user2', $auth_user_id], ['status', 1]])
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

    public static function listOutgoing()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user1', $auth_user_id], ['status', 0]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public static function listIncoming()
    {
        $auth_user_id = Auth::id();

        $friends = Friends::where([['user2', $auth_user_id], ['status', 0]])->get();

        $userIds = $friends->pluck('user1')
            ->merge($friends->pluck('user2'))
            ->diff([$auth_user_id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds);

        return $users;
    }

    public static function getFriendsModels($user)
    {
        if (Auth::guest()) {
            return null;
        }

        $auth_user_id = Auth::id();
        $user_profile_id = $user->id;

        $friend = Friends::where([['user1', $user_profile_id], ['user2', $auth_user_id]])
            ->orWhere([['user1', $auth_user_id], ['user2', $user_profile_id]])->get();

        return $friend;
    }

    public static function getFriendsForms($user1)
    {
        if (Auth::guest()) {
            return null;
        }

        $user2 = Auth::user();

        $friend = Friends::where([['user1', $user1->id], ['user2', $user2->id]])
            ->orWhere([['user1', $user2->id], ['user2', $user1->id]])->get();

        $forms = [];
        if (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == 0 && $item->user2 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Отменить заявку', 'bi-ban', route('friends.canceladdfriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == 0 && $item->user1 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Добавить в друзья', 'bi-person-fill-add', route('friends.approveaddfriend', ['user' => $user1->id]), 'btn-primary');
            $forms[] = new self('Отклонить заявку', 'bi-ban', route('friends.rejectaddfriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) {
                return $item->status == 1;
            })->isNotEmpty()
        ) {
            $forms[] = new self('Убрать из друзей', 'bi-ban', route('friends.unfriend', ['user' => $user1->id]), 'btn-secondary');
        } else {
            $forms[] = new self('Добавить в друзья', 'bi-person-fill-add', route('friends.addfriend', ['user' => $user1->id]), 'btn-primary');
        }

        return $forms;
    }

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
