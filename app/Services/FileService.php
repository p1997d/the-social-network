<?php

namespace App\Services;

use FFMpeg\FFProbe;
use App\Models\File;
use App\Services\PhotoService;
use Illuminate\Support\Facades\Auth;

class FileService
{
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
     * Сохраняет новый файл
     *
     * @param \App\Models\User $user
     * @param string $group
     * @param string $name
     * @param object $file
     * @return \App\Models\File
     */
    public static function create($user, $group, $name, $file)
    {
        $filePath = $user->id . '/' . $group . '/' . $name . '.' . $file->getClientOriginalExtension();

        $file->storeAs('files', $filePath, 'public');

        if (explode("/", $file->getMimeType())[0] == 'image') {
            PhotoService::createThumbnails($file, $filePath);
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

    /**
     * Удаляет файл
     *
     * @param int $photo
     * @return array
     */
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

    /**
     * Преобразует длительность аудиозаписи или видеозаписи из секунд в удобочитаемый формат
     *
     * @param \App\Models\File $file
     * @return string
     */
    public static function getDuration($file)
    {
        $ffprobe = FFProbe::create();
        $info = $ffprobe->format(storage_path("app/public/files/$file->path"));
        $duration = $info->get('duration');
        $formatDuration[] = gmdate("H", $duration) !== '00' ? gmdate("H", $duration) : null;
        $formatDuration[] = gmdate("i:s", $duration);
        $formatDuration = implode(':', array_filter($formatDuration));

        return $formatDuration;
    }
}
