<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\Models\ChatMessageDelete;
use App\Models\ChatSystemMessage;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public static function getChats()
    {
        $user = Auth::user();

        $chatIds = ChatMember::where('user', $user->id)->pluck('chat');

        $chats = Chat::whereIn('id', $chatIds)->get();

        return $chats;
    }

    public static function getMessages($chat)
    {
        $userMessages = ChatMessage::where([['chat', $chat], ['delete_for_all', '!=', 1]])->get()->filter(function ($item) {
            return ChatMessageDelete::where([
                ['message', $item->id],
                ['user', auth()->user()->id]
            ])->doesntExist();
        });
        $systemMessages = ChatSystemMessage::where('chat', $chat)->get();

        $messages = collect([])->merge($userMessages)->merge($systemMessages)->sortByDesc('sent_at');

        return $messages;
    }

    public static function getUnreadMessagesCount($id)
    {
        if (Auth::guest()) {
            return null;
        }

        return ChatMessage::where([
            ['chat', $id],
            ['sender', '!=', Auth::id()],
            ['viewed_at', null],
            ['delete_for_all', 0]
        ])->get()->filter(function ($item) {
            return ChatMessageDelete::where([['message', $item->id], ['user', Auth::id()]])->doesntExist();
        })->count();
    }
}
