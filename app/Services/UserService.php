<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Classes\FriendsForm;
use App\Classes\Info;
use App\Models\Friends;
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

        $location = InfoService::getLocation($user->info->location);
        if ($location) {
            $info[] = new Info('Местоположение', 'bi-geo-alt', end($location)['name']);
        }

        $education = InfoService::getEducation($user->info->education);
        if ($education) {
            $info[] = new Info('Образование', 'bi-mortarboard', $education);
        }

        $familyStatus = InfoService::getFamilyStatus($user->info->family_status, $user->sex);
        if ($familyStatus) {
            $info[] = new Info('Семейное положение', 'bi-heart', $familyStatus);
        }

        return $info;
    }
}
