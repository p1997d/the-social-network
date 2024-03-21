<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class FileService
{
    public static function getSize($size)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'КБ', 'МБ', 'ГБ', 'ТБ');

        return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffixes[floor($base)];
    }

    public static function create($user, $group, $name, $file)
    {
        $filePath = $user->id . '/' . $group . '/' . $name . '.' . $file->getClientOriginalExtension();

        $file->storeAs('files', $filePath, 'public');

        if (explode("/", $file->getMimeType())[0] == 'image') {
            self::createThumbnails($file, $filePath);
        }

        $model = new File();

        $model->name = $file->getClientOriginalName();
        $model->path = $filePath;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->author = $user->id;
        $model->group = $group;

        $model->save();

        return $model;
    }

    public static function createThumbnails($file, $filePath)
    {
        $image = Image::read($file);
        $image->scale(width: 300);
        $imagedata = (string) $image->toJpeg();

        Storage::put('public/thumbnails/' . $filePath, $imagedata);
    }

    public static function delete($photo)
    {
        $file = File::find($photo);

        if ($file->author !== Auth::id()) {
            abort(403);
        }

        if (!$file->deleted_at) {
            $file->update([
                'deleted_at' => now(),
            ]);

            $button = "Восстановить";
        } else {
            $file->update([
                'deleted_at' => null,
            ]);

            $button = "Удалить";
        }

        return compact('file', 'button');
    }
}
