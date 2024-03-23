<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use function morphos\Russian\pluralize;

Carbon::setLocale('ru');

class GeneralService
{
    /**
     * Получает аватар
     *
     * @param object $model
     * @return string
     */
    public static function getAvatar($model)
    {
        $file = $model->avatarFile;

        if ($file && Storage::exists("public/files/" . $file->path)) {
            return Storage::url("public/files/" . $file->path);
        }

        $name = class_basename($model) == "User" ? "$model->firstname+$model->surname" : $model->title;

        return "https://ui-avatars.com/api/?name=$name&background=random&size=150";
    }

    /**
     * Преобразует дату в удобочитаемый формат
     *
     * @param string $time
     * @return string
     */
    public static function getDate($time)
    {
        $sentAt = Carbon::parse($time);

        $date = $sentAt->isYesterday() ? 'Вчера' :
            (
                $sentAt->isToday() ? 'Сегодня' :
                (
                    $sentAt->isCurrentYear() ?
                    $sentAt->isoFormat('D MMMM') :
                    $sentAt->isoFormat('D MMMM YYYY')
                )
            );

        return $date;
    }

    /**
     * Преобразует слово во множественное число
     *
     * @param int $count
     * @param string $text
     * @return string
     */
    public static function getPluralize($count, $text)
    {
        return pluralize($count, $text);
    }

    /**
     * Undocumented function
     *
     * @param \App\Models\User $user
     * @param string $type
     * @return string
     */
    public static function getTitle($user, $type)
    {
        if ($user->id != Auth::id()) {
            return "Мои " . mb_strtolower($type);
        }

        $genitiveName = UserService::getGenitiveName($user);
        return $type . " " . $genitiveName;
    }
}
