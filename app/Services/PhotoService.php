<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\MessageFile;
use App\Models\Photo;
use App\Models\Post;
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
     * @param [type] $user
     * @param [type] $type
     * @param [type] $to
     * @param [type] $chat
     * @return \App\Models\Photo[]
     */
    public static function getPhotos($user = null, $type = null, $to = null, $chat = null)
    {
        if (str_starts_with($type, 'post')) {
            return self::postPhotos($type);
        } else if (str_starts_with($type, 'group')) {
            return self::groupPhotos($type);
        } else {
            return match ($type) {
                'profile' => self::profilePhotos($user),
                'uploaded' => self::uploadedPhotos($user),
                'messages' => self::messagesPhotos($to, $chat),
                'wall' => self::wallPhotos($user),
                default => self::allPhotos($user),
            };
        }

    }

    private static function profilePhotos($user)
    {
        $profileIds = UserAvatar::where([['user', $user->id], ['deleted_at', null]])->pluck('avatar');
        return Photo::whereIn('id', $profileIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function uploadedPhotos($user)
    {
        $uploadedIds = UserFile::where([['user', $user->id], ['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $uploadedIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function messagesPhotos($to, $chat)
    {
        $user = User::find(Auth::id());

        if ($to) {
            $dialog = DialogService::getOrCreateDialog($user->id, $to);
            $messagesIds = MessageFile::whereIn('message', $dialog->messages()->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        } else if ($chat) {
            $chat = Chat::find($chat);
            $messagesIds = MessageFile::whereIn('message', $chat->userMessages->pluck('id'))->whereHasMorph('file', [Photo::class])->get()->pluck('file_id');
        } else {
            abort(404);
        }

        return Photo::whereIn('id', $messagesIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function postPhotos($type)
    {
        $post_id = str_replace('post', '', $type);
        $postsIds = PostFile::where([['post', $post_id], ['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $postsIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function groupPhotos($type)
    {
        $group_id = str_replace('group', '', $type);
        $postsIds = GroupFile::where([['group', $group_id], ['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $postsIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function wallPhotos($user)
    {
        $posts_id = $user->postsWithDeleted->pluck('id');
        $postsIds = PostFile::whereIn('post', $posts_id)->where([['file_type', Photo::class]])->pluck('file_id');
        return Photo::whereIn('id', $postsIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    private static function allPhotos($user)
    {
        $profileIds = self::profilePhotos($user)->pluck('id');
        $uploadedIds = self::uploadedPhotos($user)->pluck('id');
        $postsIds = self::wallPhotos($user)->pluck('id');

        $allIds = $profileIds->merge($uploadedIds)->merge($postsIds);
        return Photo::whereIn('id', $allIds)->where('deleted_at', null)->orderByDesc('created_at')->get();
    }

    public static function getAuthorLinks($type, $author)
    {
        if (str_starts_with($type, 'post')) {
            return self::getForPostLinks($type, $author);
        } else if (str_starts_with($type, 'group')) {
            return self::getForGroupLinks($type);
        } else {
            return [
                'photoModalAvatar' => $author->avatar()->thumbnailPath,
                'photoModalLink' => ['title' => "$author->firstname $author->surname", 'href' => route('profile', $author->id)],
            ];
        }
    }

    private static function getForGroupLinks($type)
    {
        $groupID = str_replace('group', '', $type);
        $group = Group::find($groupID);
        return [
            'photoModalAvatar' => $group->avatar()->thumbnailPath,
            'photoModalLink' => ['title' => $group->title, 'href' => route('groups.index', $group->id)],
        ];
    }

    private static function getForPostLinks($type, $author)
    {
        $postID = str_replace('post', '', $type);
        $post = Post::find($postID);

        if ($post->group) {
            return [
                'photoModalAvatar' => $post->group->avatar()->thumbnailPath,
                'photoModalLink' => ['title' => $post->group->title, 'href' => route('groups.index', $post->group->id)],
            ];
        }

        return [
            'photoModalAvatar' => $author->avatar()->thumbnailPath,
            'photoModalLink' => ['title' => "$author->firstname $author->surname", 'href' => route('profile', $author->id)],
        ];
    }

}
