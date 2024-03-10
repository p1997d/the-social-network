<?php

namespace App\Services;

use morphos\Russian;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Classes\Info;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;

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

    public static function getGenitiveName($user){
        $name = Russian\inflectName($user->firstname, 'родительный') . " " . Russian\inflectName($user->surname, 'родительный');
        return $name;
    }
}
