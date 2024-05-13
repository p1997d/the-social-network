<?php

namespace App\Services;

use App\Models\GroupPost;
use App\Models\Post;
use App\Models\PostFile;
use App\Models\UserPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public static function create($content)
    {
        $user = User::find(Auth::id());

        $post = new Post();

        $post->content = $content;
        $post->author = $user->id;

        $post->save();

        return $post;
    }

    public static function saveForUser($post, $user)
    {
        $user_post = new UserPost();

        $user_post->post = $post->id;
        $user_post->user = $user->id;

        $user_post->save();
    }

    public static function saveForGroup($post, $group)
    {
        $group_post = new GroupPost();

        $group_post->post = $post->id;
        $group_post->group = $group->id;

        $group_post->save();
    }

    public static function saveAttachments($attachments, $post)
    {
        $attachmentFiles = [];

        if (!$attachments || count($attachments) <= 0) {
            return $attachmentFiles;
        }

        if (count($attachments) > 10) {
            abort(422, 'Максимальное количество загружаемых файлов - 10');
        }

        foreach ($attachments as $i => $attachment) {
            $data = (object) collect(['title' => '', 'artist' => ''])->all();
            $file = FileService::create($attachment, $data);

            $model = new PostFile();
            $model->post = $post->id;
            $model->file_id = $file->id;
            $model->file_type = $file->getMorphClass();

            $model->save();

            array_push($attachmentFiles, $file);
        }

        return $attachmentFiles;
    }
}
