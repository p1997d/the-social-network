<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Dialog;
use App\Models\ChatMessage;
use App\Models\ChatMember;
use App\Models\ChatMessageDelete;

class MessagesService
{
    public static function getAttachments($attachmentsArray, $type = null)
    {
        if (!$attachmentsArray) {
            return [];
        }

        $attachmentIds = json_decode($attachmentsArray);
        $attachments = File::whereIn('id', $attachmentIds)->get();

        if ($type) {
            $attachments = $attachments->filter(function ($item) use ($type) {
                return explode('/', $item->type)[0] == $type;
            });
        }

        return $attachments;
    }

    public static function getUnreadMessagesCount()
    {
        if (Auth::guest()) {
            return null;
        }

        $unreadDialogCount = Dialog::where([
            ['recipient', Auth::id()],
            ['viewed_at', null],
            ['delete_for_recipient', '!=', 1]
        ])->get()->unique('sender')->count();

        $unreadChatCount = ChatMessage::where([
            ['sender', '!=', Auth::id()],
            ['viewed_at', null],
            ['delete_for_all', 0]
        ])->get()->filter(function ($item) {
            return ChatMember::where([['chat', $item->chat], ['user', Auth::id()]])->exists() &&
                ChatMessageDelete::where([['message', $item->id], ['user', Auth::id()]])->doesntExist();
        })->unique('chat')->count();

        $unreadMessagesCount = $unreadDialogCount + $unreadChatCount;

        return $unreadMessagesCount;
    }
}
