<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;

class ChatSystemMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_system_messages';
    protected $quarde = false;
    protected $guarded = [];

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function recipientUser()
    {
        return $this->belongsTo(User::class, 'recipient');
    }

    public function date()
    {
        return GeneralService::getDate($this->sent_at);
    }
}
