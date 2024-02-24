<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $quarde = false;
    protected $guarded = [];

    public static function getChats($user)
    {

        $chatIds = ChatMember::where('user', $user->id)->pluck('chat');

        $chats = Chat::whereIn('id', $chatIds)->get();

        return $chats;
    }

    public function getAvatar()
    {
        $chat = clone $this;

        if ($chat->avatar && Storage::exists("public/avatars/" . $chat->avatar)) {
            return Storage::url("public/avatars/" . $chat->avatar);
        }

        return "https://ui-avatars.com/api/?name=$chat->title&background=random&size=150";
    }

    public function getLastMessage()
    {
        $userMessages = ChatMessage::where('chat', $this->id)->get();
        $systemMessages = ChatSystemMessage::where('chat', $this->id)->get();

        $mergedMessages = $userMessages->merge($systemMessages)->sortBy('sent_at')->last();

        return $mergedMessages;
    }

    public static function getMessages($chat, $page)
    {
        $userMessages = ChatMessage::where([['chat', $chat], ['delete_for_all', '!=', 1]])->get()->filter(function ($item) {
            return ChatMessageDelete::where([
                ['message', $item->id],
                ['user', auth()->user()->id]
            ])->doesntExist();
        });
        $systemMessages = ChatSystemMessage::where('chat', $chat)->get();

        $messagesCollection = collect([])->merge($userMessages)->merge($systemMessages)->sortByDesc('sent_at');

        $messages = $messagesCollection->forPage($page, 25)->values();

        return $messages;
    }
}
