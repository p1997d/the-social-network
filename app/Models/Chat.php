<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GeneralService;
use App\Services\ChatService;

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

    public function lastMessage()
    {
        return ChatService::getMessages($this->id)->first();
    }

    public function unreadMessagesCount()
    {
        return ChatService::getUnreadMessagesCount($this->id);
    }
}
