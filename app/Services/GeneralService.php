<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

Carbon::setLocale('ru');

class GeneralService
{
    public static function getAvatar($model)
    {
        if (optional($model->info)->avatar && Storage::exists("public/avatars/" . $model->info->avatar)) {
            return Storage::url("public/avatars/" . $model->info->avatar);
        }

        $name = class_basename($model) == "User" ? "$model->firstname+$model->surname" : $model->title;

        return "https://ui-avatars.com/api/?name=$name&background=random&size=150";
    }

    public static function getDate($time)
    {
        $sentAt = Carbon::parse($time);

        $date = $sentAt->isYesterday() ? 'Вчера' :
        ($sentAt->isToday() ? 'Сегодня' :
        ($sentAt->isCurrentYear() ? $sentAt->isoFormat('D MMMM') : $sentAt->isoFormat('D MMMM YYYY')));

        return $date;
    }
}
