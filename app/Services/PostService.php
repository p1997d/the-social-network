<?php

namespace App\Services;

use App\Models\GroupPost;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostFile;
use App\Models\UserPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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

    public static function getPosts($posts, $page = 1)
    {
        $listPosts = $posts->map(function ($post) {
            return self::getPost($post);
        });

        return collect($listPosts)->forPage($page, 25);
    }

    public static function getPost($post)
    {
        return [
            'post' => clone $post,
            'postAttachments' => $post->attachments(),
            'postDecryptContent' => Crypt::decrypt($post->content),
            'postDate' => $post->createdAtDiffForHumans(),
            'postHeaderLink' => $post->group ? route('groups.index', $post->group->id) : route('profile', $post->authorUser->id),
            'postHeaderAvatar' => $post->group ?: $post->authorUser,
            'postHeaderAvatarModel' => $post->group ? $post->group->avatar() : $post->authorUser->avatar(),
            'postHeaderTitle' => $post->group ? $post->group->title : $post->authorUser->firstname . ' ' . $post->authorUser->surname,
            'postAdminCondition' => $post->group ? $post->group->admins()->contains('id', optional(auth()->user())->id) : optional(auth()->user())->id == $post->authorUser->id,
            'postSetLike' => [
                'id' => $post->id,
                'type' => $post->getMorphClass(),
                'data' => class_basename($post) . $post->id,
                'count' => $post->likes->count(),
                'class' => $post->myLike !== null ? 'btn btn-sm btn-outline-danger active' : 'btn btn-sm btn-outline-secondary',
            ],
            'comments' => InteractionService::getComments($post, $post->group ?? null)->forPage(1, 25),
        ];
    }

    public static function getNews($page = 1)
    {
        $user = User::find(Auth::id());
        $friends = FriendsService::listFriends($user);
        $groups = $user->groups;

        $userPosts = UserPost::whereIn('user', $friends->pluck('id'))->pluck('post');
        $groupPosts = GroupPost::whereIn('group', $groups->pluck('id'))->pluck('post');

        $listPosts = Post::whereIn('id', $userPosts->push(...$groupPosts))->where('deleted_at', null)->orderByDesc('created_at')->get()->forPage($page, 25);

        return self::getPosts($listPosts);
    }

    public static function getLikes($page = 1)
    {
        $user = User::find(Auth::id());
        $postsIds = Like::where([
            ['user', $user->id],
            ['likeable_type', Post::class]
        ])->pluck('likeable_id');

        $listPosts = Post::whereIn('id', $postsIds)->where('deleted_at', null)->orderByDesc('created_at')->get()->forPage($page, 25);

        return self::getPosts($listPosts);
    }
}
