<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friends;
use Illuminate\Support\Facades\Auth;

class FriendsService
{
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
}
