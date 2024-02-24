<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Carbon::setLocale('ru');

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $quarde = false;
    protected $guarded = [];

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function getAttachments($type = null)
    {
        if (!$this->attachments) {
            return [];
        }

        $attachmentIds = json_decode($this->attachments);
        $attachments = File::whereIn('id', $attachmentIds)->get();

        if ($type) {
            $attachments = $attachments->filter(function ($item) use ($type) {
                return explode('/', $item->type)[0] == $type;
            });
        }

        return $attachments;
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
