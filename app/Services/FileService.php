<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\Audio;
use App\Models\Video;
use App\Models\File;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\UserFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use FFMpeg\FFProbe;

class FileService
{
    /**
     * Сохраняет файл
     *
     * @param object $file
     * @param object|null $data
     * @return object
     */
    public static function create($file, $data = null)
    {
        $model = match (explode("/", $file->getMimeType())[0]) {
            'image' => PhotoService::create($file),
            'audio' => AudioService::create($file, $data),
            'video' => VideoService::create($file, $data),
            default => self::createFile($file),
        };

        return $model;
    }

    /**
     * Сохраняет файл других типов
     *
     * @param object $file
     * @return \App\Models\File
     */
    public static function createFile($file)
    {
        $user = User::find(Auth::id());
        $filePath = $user->id . '/' . 'files';
        $path = Storage::putFile('public/files/' . $filePath, $file);
        $storagePath = Storage::url($path);

        $model = new File();

        $model->path = $storagePath;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->author = $user->id;

        $model->save();

        return $model;
    }

    /**
     * Удаляет файл
     *
     * @param object $photo
     * @return array
     */
    public static function delete($file)
    {
        if (!$file) {
            abort(404);
        }

        if ($file->author !== Auth::id()) {
            abort(403);
        }

        if (!$file->deleted_at) {
            $file->update([
                'deleted_at' => now(),
            ]);

            $button = "restore";
        } else {
            $file->update([
                'deleted_at' => null,
            ]);

            $button = "delete";
        }

        return compact('file', 'button');
    }

    /**
     * Получает файлы других типов
     *
     * @param \App\Models\User $user
     * @return \App\Models\File[]
     */
    public static function getFiles($user)
    {
        return File::where([['deleted_at', null], ['author', $user->id]])->get();
    }

    /**
     * Преобразует длительность аудиозаписи или видеозаписи из секунд в удобочитаемый формат
     *
     * @param string $path
     * @return string
     */
    public static function getDuration($path)
    {
        $ffprobe = FFProbe::create();
        $info = $ffprobe->format(public_path($path));
        $duration = $info->get('duration');
        $formatDuration[] = gmdate("H", $duration) !== '00' ? gmdate("H", $duration) : null;
        $formatDuration[] = gmdate("i:s", $duration);
        $formatDuration = implode(':', array_filter($formatDuration));

        return $formatDuration;
    }

    /**
     * Преобразует размер файла из байтов в удобочитаемый формат
     *
     * @param int $size
     * @return string
     */
    public static function getSize($size)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'КБ', 'МБ', 'ГБ', 'ТБ');

        return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Undocumented function
     *
     * @param \App\Models\User $user
     * @param object $file
     * @return void
     */
    public static function saveForUser($user, $file)
    {
        $model = new UserFile();
        $model->user = $user->id;
        $model->file_id = $file->id;
        $model->file_type = $file->getMorphClass();

        $model->save();
    }

    public static function uploadFile($type, $file)
    {
        $user = User::find(Auth::id());
        $filePath = $user->id . '/' . $type;
        $path = Storage::putFile('public/files/' . $filePath, $file);
        return Storage::url($path);
    }
}
