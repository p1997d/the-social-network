<?php

namespace App\Services;

use App\Models\Dialog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DialogService
{
    public static function getDialogs()
    {
        $sender = Auth::user();
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

    public static function getUnreadMessagesCount($id)
    {
        if (Auth::guest()) {
            return null;
        }

        return Dialog::where([
            ['sender', $id],
            ['recipient', Auth::id()],
            ['viewed_at', null],
            ['delete_for_recipient', 0],
        ])->count();
    }
}
