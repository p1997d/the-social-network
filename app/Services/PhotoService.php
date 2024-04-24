<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\MessageFile;
use App\Models\Photo;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\UserFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PhotoService
{
    /**
     * Загружает новую фотографию
     *
     * @param object $file
     * @return \App\Models\Photo
     */
    public static function create($file)
    {
        $type = 'photos';
        $image = Image::read($file);
        $user = User::find(Auth::id());

        $path = FileService::uploadFile($type, $file);

        $thumbnailPath = self::createThumbnails($image, $type, $user, basename($path));
        $storageThumbnailPath = Storage::url($thumbnailPath);

        $model = new Photo();

        $model->path = $path;
        $model->thumbnailPath = $storageThumbnailPath;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->width = $image->width();
        $model->height = $image->height();
        $model->author = $user->id;

        $model->save();

        return $model;
    }

    /**
     * Создает миниатюру изображения
     *
     * @param object $image
     * @param string $filePath
     * @param string $fileName
     * @return string
     */
    public static function createThumbnails($image, $type, $user, $fileName)
    {
        $filePath = $user->id . '/' . $type;
        $image->scale(width: 300);
        $imagedata = (string) $image->toJpeg();

        $path = 'public/thumbnails/' . $filePath . '/' . $fileName;

        Storage::put($path, $imagedata, 'public');

        return $path;
    }

    /**
     * Получает фотографии
     *
     * @param \App\Models\User $user
     * @param string $type
     * @return \App\Models\Photo[]
     */
    public static function getPhotos($user, $type = null, $to = null, $chat = null)
    {
        $profileIds = UserAvatar::where([['user', $user->id], ['deleted_at', null]])->pluck('avatar');
        $uploadedIds = UserFile::where([['user', $user->id], ['file_type', Photo::class]])->pluck('file_id');
        $allIds = $profileIds->merge($uploadedIds);

        if ($to) {
            $dialog = DialogService::getOrCreateDialog($user->id, $to);
            $messagesIds = MessageFile::whereIn('message', $dialog->messages()->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        }
        if ($chat) {
            $chat = Chat::find($chat);
            $messagesIds = MessageFile::whereIn('message', $chat->userMessages->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        }

        return match($type) {
            'profile' => Photo::whereIn('id', $profileIds)->where('deleted_at', null)->get(),
            'uploaded' => Photo::whereIn('id', $uploadedIds)->where('deleted_at', null)->get(),
            'messages' => Photo::whereIn('id', $messagesIds)->where('deleted_at', null)->get(),
            default => Photo::whereIn('id', $allIds)->where('deleted_at', null)->get(),
        };
    }
}
