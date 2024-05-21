<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeneralService;
use Carbon\Carbon;

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

    public function sentTheSameDay($message)
    {
        return Carbon::parse($this->sent_at)->isSameDay(Carbon::parse($message->sent_at));
    }
}
