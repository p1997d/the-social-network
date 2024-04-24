<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Controller;
use App\Services\MessagesService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Services\ChatService;
use App\Services\GeneralService;
use App\Services\FriendsService;
use App\Services\DialogService;

class IndexController extends Controller
{
    /**
     * Отображает страницу сообщений пользователя
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function messages(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $title = 'Сообщения';

        $sender = User::find(Auth::id());

        $to = $request->query('to');
        $chat = $request->query('chat');
        $query = $request->query('query');

        if ($query) {
            $messages = MessagesService::search($query, $to, $chat, $sender);
            return view('messenger.search', compact('title', 'messages', 'query'));
        }

        if ($to) {
            return view('messenger.chat', DialogService::getDialog($to, $sender, $title));
        } else if ($chat) {
            $page = $request->page ?? 1;
            return view('messenger.chat', ChatService::getChat($page, $chat, $title));
        } else {
            $chatLogs = $sender->dialogsAndChatsWithMessages();
            $friends = FriendsService::listFriends($sender)->get();
            return view('messenger.list', compact('title', 'chatLogs', 'friends'));
        }
    }

    /**
     * Получить содержимое сообщения
     *
     * @param Request $request
     * @return array
     */
    public function getMessage(Request $request)
    {
        $user = User::find(Auth::id());
        $message = Message::find($request->id);

        if ($message->author !== $user->id) {
            abort(403);
        }

        $content = Crypt::decrypt($message->content);
        // $attachments = $message->attachments();
        $attachments = [];

        return compact('content', 'attachments');
    }
}
