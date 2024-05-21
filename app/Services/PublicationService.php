<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PublicationService
{
    protected $request;

    public static function getPage($type, $model, $request)
    {
        return match ($type) {
            'photo' => self::getPhotosPage($model, $request),
            'audio' => self::getAudiosPage($model),
            'video' => self::getVideosPage($model),
        };
    }


    public static function getPhotosPage($model, $request)
    {
        $publicationsType = "Фотографии";
        $info = self::getInfo($model, $publicationsType);
        $data = match (class_basename($model)) {
            'User' => [
                "type" => $request->query('type'),
                "photos" => PhotoService::getPhotos($model, $request->query('type')),
            ],
            'Group' => [
                "type" => "group$model->id",
                "photos" => PhotoService::getPhotos(null, "group$model->id")
            ],
        };

        return array_merge($data, $info);
    }

    public static function getAudiosPage($model)
    {
        $type = "Аудиозаписи";
        $info = self::getInfo($model, $type);
        $playlist = $model->playlist;
        $data = [
            'playlist' => $playlist,
            'audios' => AudioService::getAudios($playlist),
        ];

        return array_merge($data, $info);
    }

    public static function getVideosPage($model)
    {
        $type = "Видеозаписи";
        $info = self::getInfo($model, $type);
        $data = [
            'videos' => $model->videos,
        ];

        return array_merge($data, $info);
    }

    public static function getInfo($model, $type)
    {
        return match (class_basename($model)) {
            'User' => self::getUserInfo($model, $type),
            'Group' => self::getGroupInfo($model, $type),
        };
    }

    public static function getUserInfo($user, $type)
    {
        $title = GeneralService::getTitle($user, $type);
        $hasPermission = Auth::id() === $user->id;

        if ($user->id === Auth::id()) {
            $emptyMessage = "Вы ещё не загружали " . mb_strtolower($type);
        } else {
            $suffix = $user->sex == 'female' ? 'а' : '';
            $emptyMessage = "$user->firstname ещё не добавил$suffix " . mb_strtolower($type);
        }

        return compact('title', 'emptyMessage', 'hasPermission', 'user');
    }

    public static function getGroupInfo($group, $type)
    {
        $title = "$type сообщества";
        $emptyMessage = 'Пока никто не добавил ' . mb_strtolower($type);
        $hasPermission = $group->admins()->contains('id', auth()->user()->id);

        return compact('title', 'emptyMessage', 'hasPermission', 'group');
    }



}
