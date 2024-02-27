<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Classes\FriendsForm;
use App\Classes\Info;
use App\Models\Friends;
use App\Models\Location;
use Carbon\Carbon;
use Carbon\CarbonInterface;

Carbon::setLocale('ru');

class UserService
{
    public static function isOnline($id)
    {
        $status = Cache::get('online-' . $id);
        $wasOnline = "был в сети " . Carbon::parse(Cache::get('wasOnline-' . $id))->diffForHumans();
        $mobile = Cache::get('onlineMobile-' . $id);
        $online = $status ? 'online' : $wasOnline;

        return compact('status', 'online', 'mobile');
    }

    public static function getFriendsForms($user1)
    {
        $user2 = Auth::user();

        $friend = Friends::where([['user1', $user1->id], ['user2', $user2->id]])
            ->orWhere([['user1', $user2->id], ['user2', $user1->id]])->get();

        $forms = [];
        if (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == 0 && $item->user2 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Отменить заявку', 'bi-ban', route('friends.canceladdfriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) use ($user1) {
                return $item->status == 0 && $item->user1 == $user1->id;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Добавить в друзья', 'bi-person-fill-add', route('friends.approveaddfriend', ['user' => $user1->id]), 'btn-primary');
            $forms[] = new FriendsForm('Отклонить заявку', 'bi-ban', route('friends.rejectaddfriend', ['user' => $user1->id]), 'btn-secondary');
        } elseif (
            $friend->filter(function ($item) {
                return $item->status == 1;
            })->isNotEmpty()
        ) {
            $forms[] = new FriendsForm('Убрать из друзей', 'bi-ban', route('friends.unfriend', ['user' => $user1->id]), 'btn-secondary');
        } else {
            $forms[] = new FriendsForm('Добавить в друзья', 'bi-person-fill-add', route('friends.addfriend', ['user' => $user1->id]), 'btn-primary');
        }

        return $forms;
    }

    public static function getInfo($user)
    {
        $info = [];
        $info[] = new Info(
            'День рождения',
            'bi-gift',
            Carbon::parse($user->birth)->isoFormat('D MMMM YYYY') . ' (' . Carbon::parse($user->birth)->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]) . ')'
        );

        if (!$user->info) {
            return $info;
        }

        if ($user->info->location) {
            $location = json_decode($user->info->location);
            $info[] = new Info('Местоположение', 'bi-geo-alt', Location::find(end($location))->name);
        }

        if ($user->info->education) {
            $info[] = new Info('Образование', 'bi-mortarboard', $user->info->education->description());
        }

        if ($user->info->family_status) {
            $info[] = new Info('Семейное положение', 'bi-heart', $user->info->family_status->description($user->sex));
        }

        return $info;
    }
}
