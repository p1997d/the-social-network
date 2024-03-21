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
    public static function getAvatar($model)
    {
        $file = $model->avatarFile;

        if ($file && Storage::exists("public/files/" . $file->path)) {
            return Storage::url("public/files/" . $file->path);
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

    public static function getPluralize($count, $text){
        return pluralize($count, $text);
    }

    public static function getTitleAndUser($id, $type)
    {
        $user = User::find(Auth::id());
        $title = "Мои " . mb_strtolower($type);

        if ($id && $id !== $user->id) {
            $user = User::find($id);
            $genitiveName = UserService::getGenitiveName($user);
            $title = $type . " " . $genitiveName;
        }

        return array($title, $user);
    }
}
