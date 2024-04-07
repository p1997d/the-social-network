<?php

namespace App\Services;

use App\Models\User;
use App\Models\Video;

use App\Services\FileService;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Laravel\Facades\Image;

class VideoService
{
    /**
     * Получает видеозаписи пользователя
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getVideos($user)
    {
        return Video::get()->filter(function ($item) use ($user) {
            return $item->deleted_at == null && $item->videoFile->author == $user->id;
        });
    }

    /**
     * Загружает новую видеозапись
     *
     * @param \App\Models\File $file
     * @param string $title
     * @param string $thumbnailPath
     * @return array
     */
    public static function create($file, $title, $thumbnailPath)
    {
        $duration = FileService::getDuration($file);

        $video = new Video();

        $video->title = $title;
        $video->duration = $duration;
        $video->file = $file->id;
        $video->views = 0;
        $video->thumbnail = $thumbnailPath;

        $video->save();

        return ['color' => 'success', 'message' => 'Видеозапись успешно загружена'];
    }

    /**
     * Undocumented function
     *
     * @param \App\Models\User $user
     * @param string $group
     * @param string $name
     * @param \App\Models\File $file
     * @return string
     */
    public static function createThumbnails($user, $group, $name, $file)
    {
        $thumbnailPath = storage_path("app/public/thumbnails/$user->id/$group/");

        if (!is_dir($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }

        $videoPath = storage_path("app/public/files/$file->path");
        $ffmpeg = FFMpeg::create();
        $ffprobe = FFProbe::create();
        $video = $ffmpeg->open($videoPath);
        $info = $ffprobe->format($videoPath);
        $duration = $info->get('duration');
        $frame = $video->frame(TimeCode::fromSeconds($duration / 2));
        $frame->save($thumbnailPath . "$name.jpg");

        $image = Image::read($thumbnailPath . "$name.jpg");
        $image->scale(width: 300);
        $imagedata = (string) $image->toJpeg();

        Storage::put('public/thumbnails/' . "$user->id/$group/$name.jpg", $imagedata);

        return "$user->id/$group/$name.jpg";
    }
}
