<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Carbon::setLocale('ru');

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

    public function getDate()
    {
        $sentAt = Carbon::parse($this->sent_at);

        $date = $sentAt->isYesterday() ? 'Вчера' :
            ($sentAt->isToday() ? 'Сегодня' :
                ($sentAt->isCurrentYear() ? $sentAt->isoFormat('D MMMM') : $sentAt->isoFormat('D MMMM YYYY')));

        return $date;
    }
}
