<?php

namespace App\Services;

use App\Models\User;

use App\Services\PhotoService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use morphos\Russian\NounPluralization;

class GeneralService
{
    /**
     * Получает аватар
     *
     * @param object $model
     * @return object
     */
    public static function getAvatar($model)
    {
        $file = $model->avatarFile;

        if ($file && $file->deleted_at === null) {
            return (object) collect([
                'path' => $file->path,
                'thumbnailPath' => $file->thumbnailPath,
                'default' => false,
            ])->all();
        }

        return (object) collect([
            'path' => $model->avatarDefault(),
            'thumbnailPath' => $model->avatarDefault(),
            'default' => true,
        ])->all();
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
        return number_format($count, 0, '', ' ') . ' ' . NounPluralization::pluralize($count, $text);
    }

    /**
     * Получает заголовок страницы
     *
     * @param \App\Models\User $user
     * @param string $type
     * @return string
     */
    public static function getTitle($user, $type)
    {
        if ($user->id == Auth::id()) {
            return "Мои " . mb_strtolower($type);
        }

        $genitiveName = UserService::getGenitiveName($user);
        return $type . " " . $genitiveName;
    }

    public static function getMonthNames()
    {
        $months = [];

        for ($i = 1; $i <= 12; $i++){
            $months[$i] = Carbon::create()->month($i)->getTranslatedMonthName('MMMM');
        }

        return $months;
    }
}
