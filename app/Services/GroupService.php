<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public static function create($title, $theme)
    {
        $user = User::find(Auth::id());

        $group = new Group();

        $group->title = $title;
        $group->theme = $theme;
        $group->author = $user->id;

        $group->save();

        return $group;
    }

    public static function subscribe($id)
    {
        $user = User::find(Auth::id());
        $group = Group::find($id);

        $group_user = new GroupUser();

        $group_user->group = $group->id;
        $group_user->user = $user->id;

        $group_user->save();
    }

    public static function unsubscribe($id)
    {
        $user = User::find(Auth::id());
        $group = Group::find($id);

        $group_user = GroupUser::where([
            ['group', $group->id],
            ['user', $user->id],
        ])->first();

        $group_user->delete();
    }

    public static function administered($groups, $user)
    {
        return $groups->filter(function ($item) use ($user) {
            $groups = GroupUser::where([
                ['user', $user->id],
                ['admin', 1],
            ])->pluck('group')->toArray();

            return $item->author === $user->id || in_array($item->id, $groups);
        });
    }

    public static function friendInGroup($group)
    {
        $user = User::find(Auth::id());
        $listFriends = FriendsService::listFriends($user)->pluck('id')->toArray();
        $members = $group->members()->pluck('id')->toArray();

        $friends = User::whereIn('id', array_intersect($listFriends, $members));

        $follow = 'Подписан' . ($friends->count() > 1 ? 'ы ' : ' ');

        return $friends->count() > 0 ? $follow . GeneralService::getPluralize($friends->count(), 'друг') : null;
    }

    public static function getAdmins($group)
    {
        $users = GroupUser::where([
            ['group', $group->id],
            ['admin', 1],
        ])->pluck('user');

        if (!$users->contains($group->author)) {
            $users->push($group->author);
        }

        return User::whereIn('id', $users)->get();
    }
}
