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
    /**
     * Получает список чатов
     *
     * @return \App\Models\Chat[]
     */
    public static function getChats()
    {
        $user = Auth::user();

        $chatIds = ChatMember::where('user', $user->id)->pluck('chat');

        return Chat::whereIn('id', $chatIds)->get();
    }

    /**
     * Получает список сообщений чата
     *
     * @param int $chat
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMessages($chat)
    {
        $userMessages = ChatMessage::where([['chat', $chat], ['delete_for_all', '!=', 1]])->get()->filter(function ($item) {
            return ChatMessageDelete::where([
                ['message', $item->id],
                ['user', auth()->user()->id]
            ])->doesntExist();
        });
        $systemMessages = ChatSystemMessage::where('chat', $chat)->get();

        return collect([])->merge($userMessages)->merge($systemMessages)->sortByDesc('sent_at');
    }

    /**
     * Получает количество непрочитанных сообщений
     *
     * @param int $id
     * @return int|null
     */
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
