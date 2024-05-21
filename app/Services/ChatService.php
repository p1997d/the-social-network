<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\Models\ChatMessageDelete;
use App\Models\ChatSystemMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatService
{
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

        return Chat::find($id)->messages()->where('author', '!=', auth()->user()->id)->whereNull('viewed_at')->filter(function ($item) {
            return class_basename($item) !== 'ChatSystemMessage';
        })->count();
    }

    /**
     * Создает чат
     *
     * @param string $title
     * @param \App\Models\User $user
     * @return \App\Models\Chat
     */
    public static function create($title, $user)
    {
        $chat = new Chat();

        $chat->title = $title;
        $chat->author = $user->id;
        $chat->save();

        return $chat;
    }

    /**
     * Создает системное сообщение
     *
     * @param \App\Models\User $user
     * @param \App\Models\Chat $chat
     * @param string $content
     * @return \App\Models\ChatSystemMessage
     */
    public static function createSystemMessage($user, $chat, $content)
    {
        $message = new ChatSystemMessage();

        $message->sender = $user->id;
        $message->recipient = null;
        $message->content = $content;
        $message->sent_at = now();

        $message->save();

        self::saveMessage($chat, $message);

        return $message;
    }

    /**
     * Добавляет пользователя в чат
     *
     * @param \App\Models\User $user
     * @param \App\Models\Chat $chat
     * @return \App\Models\ChatMember
     */
    public static function addMember($user, $chat)
    {
        $member = new ChatMember();

        $count = ChatMember::where('user', $user->id)->count();

        $member->user = $user->id;
        $member->chat = $chat->id;
        $member->id_for_user = $count + 1;
        $member->admin = true;
        $member->joined_at = now();

        $member->save();

        return $member;
    }

    /**
     * Добавляет несколько пользователей в чат
     *
     * @param array $users
     * @param \App\Models\Chat $chat
     * @return void
     */
    public static function addMembers($users, $chat)
    {
        $usersData = [];
        foreach ($users as $userId) {
            $count = ChatMember::where('user', $userId)->count();

            $usersData[] = [
                'user' => $userId,
                'chat' => $chat->id,
                'id_for_user' => $count + 1,
                'admin' => false,
                'joined_at' => now(),
            ];
        }

        ChatMember::insert($usersData);
    }

    /**
     * Создает сообщение
     *
     * @param string $content
     * @param \App\Models\User $sender
     * @param mixed $sentAt
     * @param \App\Models\Chat $chat
     * @return \App\Models\Message
     */
    public static function createMessage($content, $sender, $sentAt, $chat)
    {
        $message = MessagesService::create($content, $sender, $sentAt);

        self::saveMessage($chat, $message);

        return $message;
    }

    /**
     * Сохраняет сообщение в чате
     *
     * @param \App\Models\Chat $chat
     * @param mixed $message
     * @return \App\Models\ChatMessage
     */
    public static function saveMessage($chat, $message)
    {
        $cm = new ChatMessage();
        $cm->chat = $chat->id;
        $cm->message_id = $message->id;
        $cm->message_type = $message->getMorphClass();
        $cm->save();

        return $cm;
    }

    /**
     * Получает данные о чате
     *
     * @param integer $page
     * @param \App\Models\Chat $chat
     * @param string $title
     * @return array
     */
    public static function getChat($page, $chat, $title)
    {
        $recipient = Chat::find($chat);

        if (!$recipient) {
            return redirect()->route('messages');
        }

        $messages = $recipient->messages()->sortByDesc('sent_at')->forPage($page, 25)->values();

        return compact('messages', 'recipient', 'title');
    }
}
