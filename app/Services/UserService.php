<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Services\InfoService;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use morphos\Russian\Cases;
use function morphos\Russian\inflectName;

class UserService
{
    /**
     * Получает статус пользователя
     *
     * @param int $id
     * @return array
     */
    public static function isOnline($id)
    {
        $user = User::find($id);
        $status = Cache::get('online-' . $id);
        $wasOnline = ($user->sex == 'female' ? 'была' : 'был') . " в сети " . Carbon::parse(Cache::get('wasOnline-' . $id))->diffForHumans();
        $mobile = Cache::get('onlineMobile-' . $id);
        $online = $status ? 'online' : $wasOnline;

        return compact('status', 'online', 'mobile');
    }

    /**
     * Получает информацию о пользователе
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function getInfo($user)
    {
        $info = [];
        $info[] = new InfoService(
            'День рождения',
            'bi-gift',
            Carbon::parse($user->birth)->isoFormat('D MMMM YYYY') . ' (' . Carbon::parse($user->birth)->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]) . ')'
        );

        if (!$user->info) {
            return $info;
        }

        if ($user->info->location) {
            $location = json_decode($user->info->location);
            $info[] = new InfoService('Местоположение', 'bi-geo-alt', Location::find(end($location))->name);
        }

        if ($user->info->education) {
            $info[] = new InfoService('Образование', 'bi-mortarboard', $user->info->education->description());
        }

        if ($user->info->family_status) {
            $info[] = new InfoService('Семейное положение', 'bi-heart', $user->info->family_status->description($user->sex));
        }

        return $info;
    }

    /**
     * Получает имя пользователя в родительном падеже
     *
     * @param \App\Models\User $user
     * @return string
     */
    public static function getGenitiveName($user)
    {
        $name = inflectName("$user->surname $user->firstname", Cases::RODIT, $user->sex[0] ?? null);
        $name = implode(' ', array_reverse(explode(' ', $name)));
        return $name;
    }
}
