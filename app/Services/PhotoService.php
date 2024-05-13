<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\MessageFile;
use App\Models\Photo;
use App\Models\PostFile;
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
        return match (true) {
            $type === 'profile' => self::profilePhotos($user),
            $type === 'uploaded' => self::uploadedPhotos($user),
            $type === 'messages' => self::messagesPhotos($user, $to, $chat),
            str_starts_with($type, 'post') => self::postPhotos($type),
            $type === null => self::allPhotos($user),
        };
    }

    private static function profilePhotos($user)
    {
        $profileIds = UserAvatar::where([['user', $user->id], ['deleted_at', null]])->pluck('avatar');
        return Photo::whereIn('id', $profileIds)->where('deleted_at', null)->get();
    }

    private static function uploadedPhotos($user)
    {
        $uploadedIds = UserFile::where([['user', $user->id], ['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $uploadedIds)->where('deleted_at', null)->get();
    }

    private static function messagesPhotos($user, $to, $chat)
    {
        if ($to) {
            $dialog = DialogService::getOrCreateDialog($user->id, $to);
            $messagesIds = MessageFile::whereIn('message', $dialog->messages()->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        }
        if ($chat) {
            $chat = Chat::find($chat);
            $messagesIds = MessageFile::whereIn('message', $chat->userMessages->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        }

        return Photo::whereIn('id', $messagesIds)->where('deleted_at', null)->get();
    }

    private static function postPhotos($type)
    {
        $post_id = str_replace('post', '', $type);
        $postsIds = PostFile::where([['post', $post_id], ['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $postsIds)->where('deleted_at', null)->get();
    }

    private static function allPhotos($user)
    {
        $profileIds = UserAvatar::where([['user', $user->id], ['deleted_at', null]])->pluck('avatar');
        $uploadedIds = UserFile::where([['user', $user->id], ['file_type', Photo::class]])->pluck('file_id');

        $allIds = $profileIds->merge($uploadedIds);
        return Photo::whereIn('id', $allIds)->where('deleted_at', null)->get();
    }
}
