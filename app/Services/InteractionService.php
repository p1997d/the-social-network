<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Dialog;
use App\Models\Group;
use App\Models\MessageFile;
use App\Models\PostFile;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionService
{
    public static function getComments($model, $group = null)
    {
        return $model->comments->map(function ($comment) use ($model, $group) {
            $author = $comment->authorUser;
            return (object) [
                'comment' => $comment,
                'id' => $comment->id,
                'content' => Crypt::decrypt($comment->content),
                'createdAtDiffForHumans' => $comment->createdAtDiffForHumans(),
                'createdAtIsoFormat' => $comment->createdAtIsoFormat(),
                'author' => [
                    'id' => $author->id,
                    'firstname' => $author->firstname,
                    'surname' => $author->surname,
                    'avatar' => $author->avatar(),
                ],
                'authorUser' => $author,
                'permission' => optional(auth()->user())->id === $comment->author ||
                    optional(auth()->user())->id === $model->author ||
                    (isset($group) && $group->admins()->contains('id', auth()->user()->id)),
            ];
        });
    }

    public static function share(Request $request)
    {
        return match ($request->radioShare) {
            'message' => self::shareInMessage($request),
            'group' => self::shareInGroup($request),
            'page' => self::shareInPage($request),
        };
    }

    public static function shareInMessage(Request $request)
    {
        list($type, $id) = explode('_', $request->selectShareInMessage);

        $content = Crypt::encrypt($request->content);
        $sender = User::find(Auth::id());
        $sentAt = now();

        $message = match ($type) {
            'Dialog' => DialogService::createMessage($content, $sender, $sentAt, Dialog::find($id)),
            'Chat' => ChatService::createMessage($content, $sender, $sentAt, Chat::find($id)),
        };

        $attachment = new MessageFile();
        $attachment->message = $message->id;
        $attachment->file_id = $request->id;
        $attachment->file_type = $request->type;

        $attachment->save();
    }
    public static function shareInGroup(Request $request)
    {
        $group = Group::find($request->selectShareInGroup);

        $content = Crypt::encrypt($request->content);

        $post = PostService::create($content);
        PostService::saveForGroup($post, $group);

        $attachment = new PostFile();
        $attachment->post = $post->id;
        $attachment->file_id = $request->id;
        $attachment->file_type = $request->type;

        $attachment->save();
    }
    public static function shareInPage(Request $request)
    {
        $user = User::find(Auth::id());

        $content = Crypt::encrypt($request->content);

        $post = PostService::create($content);
        PostService::saveForUser($post, $user);

        $attachment = new PostFile();
        $attachment->post = $post->id;
        $attachment->file_id = $request->id;
        $attachment->file_type = $request->type;

        $attachment->save();
    }
}
