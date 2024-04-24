<?php

namespace App\Services;

use App\Models\Video;
use App\Models\File;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\UserFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;

class VideoService
{
    /**
     * Загружает новую видеозапись
     *
     * @param \App\Models\File $file
     * @param object $data
     * @return \App\Models\Video
     */
    public static function create($file, $data)
    {
        $type = 'videos';
        $user = User::find(Auth::id());
        $path = FileService::uploadFile($type, $file);

        $duration = FileService::getDuration($path);
        $thumbnailPath = self::createThumbnails($path, $type, $user);
        $storageThumbnailPath = Storage::url($thumbnailPath);

        $model = new Video();

        $model->title = $data->title;
        $model->duration = $duration;
        $model->views = 0;
        $model->path = $path;
        $model->thumbnailPath = $storageThumbnailPath;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->author = $user->id;

        $model->save();

        return $model;
    }


    /**
     * Получает видеозаписи
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getVideos($user)
    {
        $videoIds = UserFile::where([['user', $user->id], ['file_type', Video::class]])->pluck('file_id');
        return Video::whereIn('id', $videoIds)->where('deleted_at', null)->get();
    }

    /**
     * Создает превью для видео
     *
     * @param \App\Models\User $user
     * @param string $group
     * @param string $name
     * @param \App\Models\File $file
     * @return string
     */
    public static function createThumbnails($path, $type, $user)
    {
        $filePath = $user->id . '/' . $type;

        $ffmpeg = FFMpeg::create();
        $ffprobe = FFProbe::create();

        $video = $ffmpeg->open(public_path($path));
        $info = $ffprobe->format(public_path($path));

        $duration = $info->get('duration');
        $frame = $video->frame(TimeCode::fromSeconds($duration / 2));

        $image = Image::read($frame->save('', false, true));
        $image->scale(width: 300);
        $imagedata = (string) $image->toJpeg();

        $thumbnailPath = 'public/thumbnails/' . $filePath . '/' . pathinfo($path, PATHINFO_FILENAME) . '.jpg';

        Storage::put($thumbnailPath, $imagedata, 'public');

        return $thumbnailPath;
    }
}
