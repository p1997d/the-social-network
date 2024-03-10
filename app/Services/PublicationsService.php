<?php

namespace App\Services;

use App\Models\File;
use App\Models\User;
use App\Services\DialogService;
use App\Services\ChatService;
use App\Services\MessagesService;
use Illuminate\Support\Facades\Auth;

class PublicationsService
{
    public static function getPhotos($user, $type = null, $to = null, $chat = null)
    {
        switch ($type) {
            case 'profile':
                $photos = File::where([
                    ['author', $user->id],
                    ['group', 'avatars'],
                    ['type', 'like', 'image/%'],
                    ['deleted_at', null]
                ]);
                break;

            case 'uploaded':
                $photos = File::where([
                    ['author', $user->id],
                    ['group', 'photos'],
                    ['type', 'like', 'image/%'],
                    ['deleted_at', null]
                ]);
                break;
            case 'messages':
                if ($to) {
                    $user1 = User::find(Auth::id());
                    $user2 = User::find($to);

                    $messages = DialogService::getMessages($user1, $user2)->get();

                } elseif ($chat) {
                    $messages = ChatService::getMessages($chat);
                }

                $messages->filter(function ($message) {
                    return $message->attachments !== null;
                });

                $attachments = collect();

                foreach ($messages as $message) {
                    $attachment = MessagesService::getAttachments($message->attachments, 'image');
                    $attachments = $attachments->merge($attachment);
                }

                return $attachments->sortBy('created_at');
            default:
                $photos = File::where([
                    ['author', $user->id],
                    ['group', 'avatars'],
                    ['type', 'like', 'image/%'],
                    ['deleted_at', null]
                ])->orWhere([
                            ['author', $user->id],
                            ['group', 'photos'],
                            ['type', 'like', 'image/%'],
                            ['deleted_at', null]
                        ]);
                break;
        }

        $photos = $photos->get()->sortByDesc('created_at');

        return $photos;
    }
}
