<?php

namespace App\Http\Controllers\Messanger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\Messages;
use App\Models\User;
use App\Models\Dialog;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\Models\ChatSystemMessage;
use App\Models\ChatMessageDelete;
use Illuminate\Support\Facades\Crypt;
use morphos\Russian;


class IndexController extends Controller
{
    public function messages(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $title = 'Сообщения';

        $sender = Auth::user();

        $to = $request->query('to');
        $chat = $request->query('chat');
        $query = $request->query('query');

        if ($query) {
            if ($to) {
                $dialog = Dialog::where([['sender', $sender->id], ['recipient', $to], ['delete_for_sender', 0]])->orWhere([['sender', $to], ['recipient', $sender->id], ['delete_for_recipient', 0]]);
            } else {
                $dialog = Dialog::where([['sender', $sender->id], ['delete_for_sender', 0]])->orWhere([['recipient', $sender->id], ['delete_for_recipient', 0]]);
            }
            $messages = $dialog->get()->filter(function ($item) use ($query) {
                return str_contains(Crypt::decrypt($item->content), $query);
            })->values();

            return view('messenger.search', compact('title', 'messages', 'query'));
        }

        if ($to) {
            $type = 'dialog';
            $recipient = User::find($to);

            if (!$recipient) {
                return view('main.info', ['info' => 'Страница удалена либо ещё не создана.']);
            }

            $messages = Dialog::getMessages($sender, $recipient)->paginate(25)->appends(['to' => $to])->onEachSide(1);

            return view('messenger.chat', compact('type', 'title', 'recipient', 'messages'));
        } else if ($chat) {
            $type = 'chat';
            $page = $request->page ?? 1;

            $recipient = Chat::find($chat);
            $members = ChatMember::where('chat', $chat);

            if (!$recipient) {
                return redirect()->route('messages');
            }

            $countMembers = Russian\pluralize($members->count(), 'участник');

            $messages = Chat::getMessages($chat, $page);

            return view('messenger.chat', compact('type', 'title', 'messages', 'recipient', 'countMembers'));
        } else {
            $users = Dialog::getDialogs($sender);
            $chats = Chat::getChats($sender);

            $messages = [];

            foreach ($users as $user) {
                $messages[] = new Messages($user, 'to');
            }

            foreach ($chats as $chat) {
                $messages[] = new Messages($chat, 'chat');
            }

            $friends = User::find(Auth::id())->listFriends()->get();

            return view('messenger.list', compact('title', 'messages', 'friends'));
        }
    }

    public function getMessage(Request $request)
    {
        $user = Auth::user();
        if ($request->typeRecipient == 'to'){
            $message = Dialog::find($request->id);
        }
        elseif ($request->typeRecipient == 'chat'){
            $message = ChatMessage::find($request->id);
        }


        if ($message->sender != $user->id) {
            abort(403);
        }

        $content = Crypt::decrypt($message->content);
        $attachments = $message->getAttachments();

        return compact('content', 'attachments');
    }
}
