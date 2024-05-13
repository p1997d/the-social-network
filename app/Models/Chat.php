<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;
use App\Services\ChatService;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $quarde = false;
    protected $guarded = [];

    public function avatar()
    {
        return GeneralService::getAvatar($this);
    }

    public function avatarDefault()
    {
        return "https://ui-avatars.com/api/?name=$this->title&background=random&size=150";
    }

    public function avatarFile()
    {
        return $this->hasOne(File::class, 'id', 'avatar');
    }

    public function unreadMessagesCount()
    {
        return ChatService::getUnreadMessagesCount($this->id);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'chat');
    }

    public function userMessages()
    {
        return $this->belongsToMany(Message::class, 'chat_messages', 'chat', 'message_id')
            ->wherePivot('message_type', Message::class)
            ->wherePivot('delete_for_all', 0)
            ->leftjoin('chat_message_delete', function ($join) {
                $join->on('messages.id', '=', 'chat_message_delete.message');
                $join->where('chat_message_delete.user', '=', auth()->user()->id);
            })
            ->whereNull('chat_message_delete.message')
            ->select('messages.*');
    }

    public function systemMessages()
    {
        return $this->belongsToMany(ChatSystemMessage::class, 'chat_messages', 'chat', 'message_id')
            ->wherePivot('message_type', ChatSystemMessage::class)
            ->wherePivot('delete_for_all', 0);
    }

    public function messages()
    {
        $systemMessages = $this->systemMessages;
        $userMessages = $this->userMessages;
        return $userMessages->push(...$systemMessages)->sortBy('sent_at');
    }

    public function members_count()
    {
        return GeneralService::getPluralize(
            ChatMember::where('chat', $this->id)->count(),
            'участник'
        );
    }
}
