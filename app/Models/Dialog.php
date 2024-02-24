<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

Carbon::setLocale('ru');

class Dialog extends Model
{
    use HasFactory;

    protected $table = 'dialogs';
    protected $quarde = false;
    protected $guarded = [];

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public static function getMessages($user1, $user2)
    {
        $messages = Dialog::where([
            ['sender', $user1->id],
            ['recipient', $user2->id],
            ['delete_for_sender', '!=', 1]
        ])->orWhere([
                    ['sender', $user2->id],
                    ['recipient', $user1->id],
                    ['delete_for_recipient', '!=', 1]
                ])
            ->orderBy('sent_at', 'desc');

        return $messages;
    }

    public static function getDialogs($sender)
    {
        $messages = Dialog::where([['sender', $sender->id], ['delete_for_sender', '!=', 1]])
            ->orWhere([['recipient', $sender->id], ['delete_for_recipient', '!=', 1]])
            ->get();

        $userIds = $messages->pluck('sender')
            ->merge($messages->pluck('recipient'))
            ->diff([$sender->id])
            ->unique()
            ->toArray();

        $users = User::whereIn('id', $userIds)->get();

        // $users = $users->sortBy(function ($user) {
        //     return optional($user->getLastMessage())->sent_at;
        // }, SORT_REGULAR, true);

        return $users;
    }

    public static function getUnreadMessagesCount()
    {
        if (Auth::guest()) {
            return null;
        }

        $unreadMessagesCount = Dialog::where([
            ['recipient', Auth::id()],
            ['viewed_at', null],
            ['delete_for_recipient', '!=', 1]
        ])->get()->unique('sender')->count();

        return $unreadMessagesCount;
    }

    public function getDate()
    {
        $sentAt = Carbon::parse($this->sent_at);

        $date = $sentAt->isYesterday() ? 'Вчера' :
        ($sentAt->isToday() ? 'Сегодня' :
        ($sentAt->isCurrentYear() ? $sentAt->isoFormat('D MMMM') : $sentAt->isoFormat('D MMMM YYYY')));

        return $date;
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
}
